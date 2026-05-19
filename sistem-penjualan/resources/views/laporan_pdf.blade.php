<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #222; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .summary { width: 100%; margin-bottom: 20px; }
        .summary td { padding: 8px; vertical-align: top; }
        .summary .label { font-weight: bold; width: 180px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 8px; }
        th { background: #f4f4f4; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Penjualan</h2>
        <p>Periode: {{ $from ? $from : 'Semua' }} - {{ $to ? $to : 'Sekarang' }}</p>
    </div>

    <table class="summary">
        <tr>
            <td class="label">Total Pendapatan</td>
            <td>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Total Transaksi</td>
            <td>{{ $transactions->count() }}</td>
        </tr>
        <tr>
            <td class="label">Total Keuntungan</td>
            <td>Rp {{ number_format($totalUntung, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table>
        <thead>
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
                        <td class="text-right">{{ $detail->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($detail->price_per_unit, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($detail->harga_final, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format(($detail->harga_final - $detail->price_per_unit) * $detail->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
