<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #0056b3;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .company-info {
            margin-bottom: 5px;
        }

        .company-info h1 {
            margin: 0;
            color: #0056b3;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .company-info p {
            margin: 2px 0;
            color: #666;
            font-size: 10px;
        }

        .report-title {
            text-align: center;
            margin: 15px 0;
        }

        .report-title h2 {
            margin: 0 0 10px 0;
            color: #0056b3;
            font-size: 18px;
            text-transform: uppercase;
        }

        .period-info {
            text-align: center;
            color: #666;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .summary-section {
            margin: 20px 0;
            padding: 12px;
            background: #f0f4f8;
            border-left: 4px solid #0056b3;
            border-radius: 3px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
        }

        .summary-item {
            text-align: center;
        }

        .summary-item .label {
            display: block;
            color: #666;
            font-size: 10px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .summary-item .value {
            display: block;
            color: #0056b3;
            font-size: 14px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        th {
            background: #0056b3;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            border: 1px solid #003d82;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tr:hover {
            background: #f0f4f8;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: right;
            font-size: 9px;
            color: #666;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <div class="company-info">
            <h1>SPEEDSHOP 2</h1>
            <p>Handphone • Accessories • Service</p>
            <p>Jl. Yos Sudarso No. 45, Lubuk Linggau</p>
        </div>
    </div>

    <!-- REPORT TITLE -->
    <div class="report-title">
        <h2>Laporan Penjualan</h2>
    </div>

    <!-- PERIOD INFO -->
    <div class="period-info">
        <strong>Periode:</strong> {{ $from ? \Carbon\Carbon::parse($from)->format('d M Y') : 'Semua' }} 
        s/d 
        {{ $to ? \Carbon\Carbon::parse($to)->format('d M Y') : 'Sekarang' }}
        <br>
        <strong>Dicetak:</strong> {{ now()->format('d M Y \p\u\k\u\l H:i') }}
    </div>

    <!-- SUMMARY SECTION -->
    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-item">
                <span class="label">TOTAL PENDAPATAN</span>
                <span class="value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            </div>
            <div class="summary-item">
                <span class="label">TOTAL TRANSAKSI</span>
                <span class="value">{{ $transactions->count() }}</span>
            </div>
            <div class="summary-item">
                <span class="label">TOTAL KEUNTUNGAN</span>
                <span class="value">Rp {{ number_format($totalUntung, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- DETAIL TABLE -->
    <table>
        <thead>
            <tr>
                <th>TANGGAL</th>
                <th>PELANGGAN</th>
                <th>PRODUK</th>
                <th width="8%" class="text-center">JENIS</th>
                <th width="8%" class="text-center">JML</th>
                <th width="15%" class="text-right">HARGA MODAL</th>
                <th width="15%" class="text-right">HARGA JUAL</th>
                <th width="15%" class="text-right">SUBTOTAL</th>
                <th width="15%" class="text-right">KEUNTUNGAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                @foreach($transaction->details as $detail)
                    <tr>
                        <td>{{ $transaction->transaction_date }}</td>
                        <td>{{ $transaction->customer_name }}</td>
                        <td>{{ $detail->product->name ?? 'Produk Dihapus' }}</td>
                        <td class="text-center">{{ $detail->product->type ?? '-' }}</td>
                        <td class="text-center">{{ $detail->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($detail->price_per_unit, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($detail->harga_final, 0, ',', '.') }}</td>
                        <td class="text-right"><strong>Rp {{ number_format(($detail->harga_final - $detail->price_per_unit) * $detail->quantity, 0, ',', '.') }}</strong></td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="9" class="no-data">Tidak ada data transaksi untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        <p>* Laporan ini digenerate secara otomatis oleh sistem SPEEDSHOP 2</p>
    </div>
</body>
</html>
