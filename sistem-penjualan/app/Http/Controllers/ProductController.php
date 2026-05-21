<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'karyawan') {
            abort(403);
        }

        $query = Product::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $products = $query->get();
        return view('produk', compact('products'));
    }

    public function edit($id)
    {
        if (auth()->user()->role !== 'karyawan') {
            abort(403);
        }

        $product = Product::findOrFail($id);
        return view('edit_produk', compact('product'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'karyawan') {
            abort(403);
        }

        return view('create_produk');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'karyawan') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:HP,Aksesoris',
            'price' => 'required|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
        ]);

        Product::create([
            'name' => $request->name,
            'type' => $request->type,
            'stock' => 0,
            'price' => $request->price,
            'harga_jual' => $request->harga_jual ?: $request->price,
            'has_invoice' => false,
        ]);

        return redirect('/produk')->with('success', 'Produk baru berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'karyawan') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:HP,Aksesoris',
            'price' => 'required|numeric|min:0',
            'harga_jual' => 'nullable|numeric|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->only(['name', 'type', 'price', 'harga_jual']));

        return redirect('/produk')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'karyawan') {
            abort(403);
        }

        $product = Product::findOrFail($id);
        $product->delete();

        return redirect('/produk')->with('success', 'Produk berhasil dihapus');
    }

    public function fakturPembelianView(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $query = Invoice::with('product', 'user');

        if ($request->search) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->type) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        $invoices = $query->latest()->get();
        $products = Product::orderBy('name')->get();
        return view('faktur_pembelian', compact('invoices', 'products'));
    }

    public function uploadFaktur(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'additional_stock' => 'required|integer|min:1',
            'new_harga_beli' => 'nullable|numeric|min:0.01',
            'new_harga_jual' => 'nullable|numeric|min:0.01',
            'invoice_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $product = Product::findOrFail($validated['product_id']);
            $stockAmount = $validated['additional_stock'];
            $hargaBeli = $validated['new_harga_beli'] ?? $product->price;

            if (!empty($validated['new_harga_beli'])) {
                $product->price = $validated['new_harga_beli'];
            }

            if (!empty($validated['new_harga_jual'])) {
                $product->harga_jual = $validated['new_harga_jual'];
            }

            $file = $request->file('invoice_file');
            $filename = 'faktur_' . time() . '_' . $product->id . '.' . $file->getClientOriginalExtension();
            $invoicesDir = storage_path('app/invoices');

            if (!File::exists($invoicesDir)) {
                File::makeDirectory($invoicesDir, 0755, true);
            }

            $path = 'invoices/' . $filename;
            $file->move($invoicesDir, $filename);

            Invoice::create([
                'product_id' => $product->id,
                'stock_amount' => $stockAmount,
                'invoice_file' => $path,
                'uploaded_by' => Auth::id(),
                'harga_beli' => $hargaBeli,
            ]);

            $product->stock += $stockAmount;
            $product->has_invoice = true;
            $product->save();

            return redirect('/faktur-pembelian')->with('success', 'Faktur berhasil diupload. Stok ' . $product->name . ' ditambahkan.');
        } catch (\Exception $e) {
            return redirect('/faktur-pembelian')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function downloadInvoice($id)
    {
        $invoice = Invoice::findOrFail($id);
        $path = storage_path('app/' . $invoice->invoice_file);

        if (empty($invoice->invoice_file) || !File::exists($path)) {
            return redirect('/faktur-pembelian')->with('error', 'File faktur tidak tersedia.');
        }

        return response()->file($path);
    }

    public function updateStock(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $product = Product::findOrFail($id);
        $action = $request->input('action');
        $amount = $request->input('amount', 1);

        if ($action == 'increase') {
            $product->stock += $amount;
        } elseif ($action == 'decrease') {
            $product->stock = max(0, $product->stock - $amount);
        }

        $product->save();

        return redirect('/stok')->with('success', 'Stok berhasil diperbarui');
    }

    public function updateInvoice(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $validated = $request->validate([
            'new_harga_beli' => 'nullable|numeric|min:0.01',
            'new_harga_jual' => 'nullable|numeric|min:0.01',
            'new_stock_amount' => 'nullable|integer|min:0',
            'invoice_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            $invoice = Invoice::findOrFail($id);
            $product = $invoice->product;

            if (isset($validated['new_harga_beli']) && $validated['new_harga_beli'] !== null) {
                $invoice->harga_beli = $validated['new_harga_beli'];
                $product->price = $validated['new_harga_beli'];
            }

            if (isset($validated['new_harga_jual']) && $validated['new_harga_jual'] !== null) {
                $product->harga_jual = $validated['new_harga_jual'];
            }

            if (isset($validated['new_stock_amount']) && $validated['new_stock_amount'] !== null) {
                $stockDifference = $validated['new_stock_amount'] - $invoice->stock_amount;
                if ($product->stock + $stockDifference < 0) {
                    return redirect('/faktur-pembelian')->with('error', 'Perubahan stok tidak valid: stok tidak boleh negatif.');
                }
                $product->stock += $stockDifference;
                $invoice->stock_amount = $validated['new_stock_amount'];
            }

            // Handle file upload
            if ($request->hasFile('invoice_file')) {
                $file = $request->file('invoice_file');
                $filename = 'faktur_' . time() . '_' . $product->id . '.' . $file->getClientOriginalExtension();
                $invoicesDir = storage_path('app/invoices');

                if (!File::exists($invoicesDir)) {
                    File::makeDirectory($invoicesDir, 0755, true);
                }

                // Delete old file if exists
                if (!empty($invoice->invoice_file) && File::exists(storage_path('app/' . $invoice->invoice_file))) {
                    File::delete(storage_path('app/' . $invoice->invoice_file));
                }

                $path = 'invoices/' . $filename;
                $file->move($invoicesDir, $filename);
                $invoice->invoice_file = $path;
            }

            $invoice->save();
            $product->save();

            return redirect('/faktur-pembelian')->with('success', 'Data pembelian berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect('/faktur-pembelian')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
