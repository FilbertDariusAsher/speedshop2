@extends('layouts.app')

@section('title', 'Log Layanan')

@section('content')
    <div class="mb-3">
        <div>
            <h3>Riwayat Pulsa, Paket Internet & Token</h3>
            <p class="text-muted">Lihat riwayat transaksi pulsa, paket internet, dan token listrik.</p>
        </div>
    </div>

    <form method="GET" action="/log-pulsa-token" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label>Tipe</label>
                <select name="type" class="form-control">
                    <option value="">Semua</option>
                    <option value="pulsa" {{ request('type') == 'pulsa' ? 'selected' : '' }}>Pulsa</option>
                    <option value="paket_internet" {{ request('type') == 'paket_internet' ? 'selected' : '' }}>Paket Internet</option>
                    <option value="token_listrik" {{ request('type') == 'token_listrik' ? 'selected' : '' }}>Token Listrik</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>Search (Nama/No HP/No Token)</label>
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

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Pelanggan</th>
                    <th>Tipe</th>
                    <th>Provider</th>
                    <th>No HP/Token</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}</td>
                    <td>{{ $transaction->customer_name }}</td>
                    <td>
                        @if($transaction->type == 'pulsa')
                            <span class="badge bg-primary">Pulsa</span>
                        @elseif($transaction->type == 'paket_internet')
                            <span class="badge bg-info">Paket Internet</span>
                        @else
                            <span class="badge bg-warning">Token Listrik</span>
                        @endif
                    </td>
                    <td>
                        @if($transaction->type == 'paket_internet' || $transaction->type == 'pulsa')
                            {{ $transaction->provider ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($transaction->phone_number)
                            {{ $transaction->phone_number }}
                        @elseif($transaction->token_number)
                            {{ $transaction->token_number }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($transaction->type == 'paket_internet')
                            {{ $transaction->amount ?? '-' }}
                        @elseif($transaction->type == 'pulsa')
                            @if(is_numeric($transaction->amount))
                                Rp {{ number_format(((float)$transaction->amount) * 1000, 0, ',', '.') }}
                            @elseif(isset($transaction->amount) && $transaction->amount)
                                Rp {{ $transaction->amount }}
                            @else
                                -
                            @endif
                        @elseif($transaction->type == 'token_listrik')
                            @if(isset($transaction->amount) && $transaction->amount)
                                Rp {{ number_format((float)$transaction->amount, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <strong>Rp {{ number_format($transaction->price, 0, ',', '.') }}</strong>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        Tidak ada transaksi ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->count() > 0)
    <div class="mt-3 p-3 bg-light rounded">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Total Transaksi:</strong> {{ $transactions->count() }} transaksi</p>
            </div>
            <div class="col-md-6 text-end">
                <p><strong>Total Penerimaan:</strong> Rp {{ number_format($transactions->sum('price'), 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    @endif
@endsection
