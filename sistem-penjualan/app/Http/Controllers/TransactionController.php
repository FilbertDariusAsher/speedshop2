<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class TransactionController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'karyawan') {
            abort(403);
        }

        return view('transaksi');
    }

    public function history(Request $request)
    {
        if (auth()->user()->role !== 'karyawan') {
            abort(403);
        }

        $query = Transaction::with('details.product');

        if ($request->category) {
            $query->whereHas('details.product', function($q) use ($request) {
                $q->where('type', $request->category);
            });
        }

        if ($request->date) {
            $query->whereDate('transaction_date', $request->date);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%$search%")
                  ->orWhereHas('details.product', function($subQ) use ($search) {
                      $subQ->where('name', 'like', "%$search%");
                  });
            });
        }

        $transactions = $query->get();
        return view('riwayat_transaksi', compact('transactions'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'karyawan') {
            abort(403);
        }
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price_per_unit' => 'required|numeric|min:0',
            'products.*.harga_jual' => 'required|numeric|min:0',
            'products.*.harga_final' => 'required|numeric|min:0',

            'products.*.imei' => 'nullable|array',
            'products.*.imei.*.imei1' => 'nullable|string',
            'products.*.imei.*.imei2' => 'nullable|string',
        ]);

        try {
            $total = 0;
            $details = [];

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    return back()->with('error', 'Stok ' . $product->name . ' tidak cukup');
                }

                $price = $product->price;
                $harga_jual = $product->harga_jual ?? $price;
                $harga_final_unit = $item['harga_final'] ?? $harga_jual;

                if ($product->type == 'HP') {
                    if (!isset($item['imei']) || count($item['imei']) < $item['quantity']) {
                        return back()->with('error', 'IMEI harus sesuai jumlah unit!');
                    }

                    foreach ($item['imei'] as $imeiData) {
                        if (empty($imeiData['imei1'])) {
                            return back()->with('error', 'IMEI 1 wajib diisi!');
                        }

                        $details[] = [
                            'product_id' => $product->id,
                            'quantity' => 1,
                            'price_per_unit' => $price,
                            'harga_jual' => $harga_jual,
                            'harga_final' => $harga_final_unit,
                            'imei1' => $imeiData['imei1'],
                            'imei2' => $imeiData['imei2'] ?? null,
                        ];

                        $total += $harga_final_unit;
                    }
                } else {
                    $subtotal = $item['quantity'] * $harga_final_unit;

                    $details[] = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price_per_unit' => $price,
                        'harga_jual' => $harga_jual,
                        'harga_final' => $harga_final_unit,
                        'imei1' => null,
                        'imei2' => null,
                    ];

                    $total += $subtotal;
                }
            }

            $transaction = Transaction::create([
                'customer_name' => $request->customer_name,
                'total' => $total,
                'transaction_date' => now()->toDateString(),
            ]);

            foreach ($details as $detail) {
                $detail['transaction_id'] = $transaction->id;
                TransactionDetail::create($detail);

                $product = Product::find($detail['product_id']);
                $product->stock -= $detail['quantity'];
                $product->save();
            }

            // ================= PDF GENERATION =================
            try {
                $transaction = Transaction::with('details.product')->find($transaction->id);

                $pdf = Pdf::loadView('pdf.nota', compact('transaction'));

                $notaDir = public_path('nota');
                if (!File::exists($notaDir)) {
                    File::makeDirectory($notaDir, 0755, true);
                }

                $filename = 'nota_' . $transaction->id . '.pdf';
                $pdf->save($notaDir . '/' . $filename);

                return redirect('/transaksi')->with('success', 'Transaksi berhasil!');
            } catch (\Exception $pdfError) {
                // Transaksi sudah berhasil disimpan, hanya PDF yang gagal
                return redirect('/transaksi')->with('success', 'Transaksi berhasil! (Nota PDF gagal digenerate, silakan cetak manual dari riwayat)');
            }
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Transaction Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage());
        }
    }

    public function downloadNota($id)
    {
        if (auth()->user()->role !== 'karyawan') {
            abort(403);
        }

        $transaction = Transaction::with('details.product')->findOrFail($id);
        $pdf = Pdf::loadView('pdf.nota', compact('transaction'));

        $customerSlug = preg_replace('/[^A-Za-z0-9]+/', '_', strtolower($transaction->customer_name ?? 'pelanggan'));
        $customerSlug = trim(preg_replace('/_+/', '_', $customerSlug), '_');
        $filename = 'nota_penjualan_' . ($customerSlug ?: 'pelanggan') . '_' . str_replace('-', '', $transaction->transaction_date) . '.pdf';

        return $pdf->download($filename);
    }
}