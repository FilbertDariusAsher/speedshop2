@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3>Riwayat Transaksi</h3>
            <p class="text-muted">Cari, filter, dan lihat detail transaksi.</p>
        </div>
    </div>

    <form method="GET" action="/riwayat-transaksi" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label>Kategori</label>
                <select name="category" class="form-control">
                    <option value="">Semua</option>
                    <option value="HP" {{ request('category') == 'HP' ? 'selected' : '' }}>HP</option>
                    <option value="Aksesoris" {{ request('category') == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Search (Nama Pelanggan atau Produk)</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label>Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary form-control">Cari</button>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Produk</th>
                            <th>Jenis</th>
                            <th>Qty</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Final</th>
                            <th>Subtotal</th>
                            <th>IMEI</th>
                            <th class="text-center">Aksi</th>
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
                                    <td>Rp {{ number_format($detail->quantity * $detail->harga_final, 0, ',', '.') }}</td>
                                    <td>
                                        @if($detail->imei1)
                                            {{ $detail->imei1 }}
                                            @if($detail->imei2)
                                                / {{ $detail->imei2 }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="/nota/{{ $transaction->id }}" class="btn btn-sm btn-success" title="Cetak Nota" target="_blank" rel="noopener noreferrer">
                                            <i class="bi bi-printer-fill"></i> Cetak
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-secondary py-4">Tidak ada transaksi ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection