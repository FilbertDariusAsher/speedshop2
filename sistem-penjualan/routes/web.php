<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PulsaTokenController;
use App\Http\Controllers\AdminPulsaTokenController;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login.form');
Route::post('/login', [AuthController::class, 'proses_login'])->name('login.perform');

Route::get('/logout', [AuthController::class, 'logout']);


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $monthlySales = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlySales[] = \App\Models\Transaction::whereYear('transaction_date', date('Y'))
                ->whereMonth('transaction_date', $i)
                ->sum('total');
        }

        $transactions = \App\Models\Transaction::orderBy('transaction_date')->get();

        $salesDates = $transactions->pluck('transaction_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))
            ->toArray();

        $salesAmounts = $transactions->pluck('total')->toArray();

        $totalSales = \App\Models\Transaction::sum('total');

        return view('dashboard', compact('monthlySales', 'salesDates', 'salesAmounts', 'totalSales'));
    });

    Route::get('/profil', function () {
        return view('profil');
    });

    // ================= PRODUK =================
    Route::get('/produk', [ProductController::class, 'index']);
    Route::get('/produk/create', [ProductController::class, 'create']);
    Route::post('/produk', [ProductController::class, 'store']);
    Route::get('/produk/{id}/edit', [ProductController::class, 'edit']);
    Route::put('/produk/{id}', [ProductController::class, 'update']);
    Route::delete('/produk/{id}', [ProductController::class, 'destroy']);

    // ================= TRANSAKSI =================
    Route::get('/transaksi', [TransactionController::class, 'index']);
    Route::post('/transaksi', [TransactionController::class, 'store']);

    Route::get('/riwayat-transaksi', [TransactionController::class, 'history']);
    Route::get('/nota/{id}', [TransactionController::class, 'downloadNota']);

    // ================= STOK =================
    Route::get('/stok', function (Request $request) {
        $query = \App\Models\Product::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $products = $query->get();
        return view('stok', compact('products'));
    });

    Route::post('/upload-faktur', [ProductController::class, 'uploadFaktur']);
    Route::get('/faktur-pembelian', [ProductController::class, 'fakturPembelianView']);
    Route::get('/invoice/{id}', [ProductController::class, 'downloadInvoice'])->name('invoice.download');
    Route::put('/invoice/{id}', [ProductController::class, 'updateInvoice'])->name('invoice.update');
    Route::post('/stok/{id}/update', [ProductController::class, 'updateStock']);

    // ================= LAPORAN =================
    Route::get('/laporan', function (Request $request) {
        $from = $request->from;
        $to = $request->to;

        $query = Transaction::with('details.product');

        if ($from) {
            $query->whereDate('transaction_date', '>=', $from);
        }
        if ($to) {
            $query->whereDate('transaction_date', '<=', $to);
        }

        $transactions = $query->orderByDesc('transaction_date')->get();
        $totalPendapatan = $transactions->sum('total');
        $totalUntung = $transactions->flatMap(function ($transaction) {
            return $transaction->details;
        })->sum(function ($detail) {
            return ($detail->harga_final - $detail->price_per_unit) * $detail->quantity;
        });

        if ($request->download === 'pdf') {
            $fromLabel = $from ? \Carbon\Carbon::parse($from)->format('Ymd') : 'awal';
            $toLabel = $to ? \Carbon\Carbon::parse($to)->format('Ymd') : 'akhir';
            $filename = 'laporan_penjualan_' . $fromLabel . '_sampai_' . $toLabel . '.pdf';
            if (!$from && !$to) {
                $filename = 'laporan_penjualan_keseluruhan_' . now()->format('Ymd') . '.pdf';
            }

            $pdf = Pdf::loadView('laporan_pdf', compact('transactions', 'from', 'to', 'totalPendapatan', 'totalUntung'));
            return $pdf->download($filename);
        }

        return view('laporan', compact('transactions', 'from', 'to', 'totalPendapatan', 'totalUntung'));
    });

    // ================= PULSA TOKEN =================
    Route::get('/pulsa-token', [PulsaTokenController::class, 'index'])->name('pulsa.index');

    Route::post('/pulsa-token', [PulsaTokenController::class, 'store'])->name('pulsa.store');

    Route::get('/log-pulsa-token', [PulsaTokenController::class, 'history'])->name('pulsa.history');

    // ================= ADMIN: PULSA & TOKEN SETTINGS =================
    Route::get('/admin/pulsa-settings', [AdminPulsaTokenController::class, 'pulsaSettings'])->name('admin.pulsa.settings');
    Route::post('/admin/pulsa-provider', [AdminPulsaTokenController::class, 'storeProvider'])->name('admin.pulsa.provider.store');
    Route::delete('/admin/pulsa-provider/{id}', [AdminPulsaTokenController::class, 'deleteProvider'])->name('admin.pulsa.provider.delete');
    Route::post('/admin/pulsa-nominal', [AdminPulsaTokenController::class, 'storeNominal'])->name('admin.pulsa.nominal.store');
    Route::put('/admin/pulsa-nominal/{id}', [AdminPulsaTokenController::class, 'updateNominal'])->name('admin.pulsa.nominal.update');
    Route::delete('/admin/pulsa-nominal/{id}', [AdminPulsaTokenController::class, 'deleteNominal'])->name('admin.pulsa.nominal.delete');

    Route::get('/admin/token-settings', [AdminPulsaTokenController::class, 'tokenSettings'])->name('admin.token.settings');
    Route::post('/admin/token-nominal', [AdminPulsaTokenController::class, 'storeTokenNominal'])->name('admin.token.nominal.store');
    Route::put('/admin/token-nominal/{id}', [AdminPulsaTokenController::class, 'updateTokenNominal'])->name('admin.token.nominal.update');
    Route::delete('/admin/token-nominal/{id}', [AdminPulsaTokenController::class, 'deleteTokenNominal'])->name('admin.token.nominal.delete');

    // ================= ADMIN: KELOLA KARYAWAN =================
    Route::get('/admin/karyawan', [EmployeeController::class, 'index'])->name('admin.karyawan.index');
    Route::post('/admin/karyawan', [EmployeeController::class, 'store'])->name('admin.karyawan.store');
    Route::put('/admin/karyawan/{id}/status', [EmployeeController::class, 'updateStatus'])->name('admin.karyawan.status');

});