@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>Laporan Penjualan</h3>
            <p class="text-muted">Filter periode dan cetak laporan pendapatan serta keuntungan.</p>
        </div>
        <div>
            <a href="/laporan?from={{ request('from') }}&to={{ request('to') }}&download=pdf" class="btn btn-outline-primary px-4 py-2 shadow-sm rounded-4">
                <i class="bi bi-printer-fill me-2"></i>Cetak PDF
            </a>
        </div>
    </div>

    <form method="GET" action="/laporan" class="row g-3 mb-4">
        <div class="col-md-4">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="from" value="{{ request('from') }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="to" value="{{ request('to') }}" class="form-control">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button class="btn btn-primary w-100">Terapkan Filter</button>
        </div>
    </form>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card report-card bg-primary text-white shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="bi bi-wallet2 fs-1 mb-3"></i>
                    <h5 class="mb-3">Total Pendapatan</h5>
                    <h2 class="mb-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
                    <p class="mb-0 opacity-75">Periode: {{ request('from') ? request('from') : 'Semua' }} - {{ request('to') ? request('to') : 'Sekarang' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card report-card bg-success text-white shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="bi bi-receipt fs-1 mb-3"></i>
                    <h5 class="mb-3">Total Transaksi</h5>
                    <h2 class="mb-2">{{ $transactions->count() }}</h2>
                    <p class="mb-0 opacity-75">Jumlah transaksi dalam periode</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card report-card bg-warning text-white shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="bi bi-graph-up-arrow fs-1 mb-3"></i>
                    <h5 class="mb-3">Total Keuntungan</h5>
                    <h2 class="mb-0">Rp {{ number_format($totalUntung, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th>Produk</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Harga Modal</th>
                            <th>Harga Jual</th>
                            <th>Subtotal</th>
                            <th>Keuntungan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            @foreach($transaction->details as $detail)
                                <tr>
                                    <td>{{ $transaction->transaction_date }}</td>
                                    <td>{{ $transaction->customer_name }}</td>
                                    <td>{{ $detail->product->name ?? 'Produk Dihapus' }}</td>
                                    <td>{{ $detail->product->type ?? '-' }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>Rp {{ number_format($detail->price_per_unit, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($detail->harga_final, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format(($detail->harga_final - $detail->price_per_unit) * $detail->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-secondary py-4">Tidak ada transaksi untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .report-card {
            border: none;
            border-radius: 18px;
            min-height: 200px;
        }
        .report-card h5 {
            font-weight: 600;
        }
        .report-card h2 {
            font-size: 2rem;
            font-weight: 700;
        }
    </style>
@endsection
