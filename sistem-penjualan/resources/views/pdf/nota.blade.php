<style>
@page {
    size: A5 landscape;
    margin: 0;
}

body {
    font-family: 'Segoe UI', Arial, sans-serif;
    font-size: 10px;
    margin: 0;
    padding: 3mm; 
    color: #333;
}

.nota-wrapper {
    border: 1.5px solid #0056b3;
    border-radius: 4px;
    padding: 8px;
    background: #fff;
 
    height: 132mm; 
    display: flex;
    flex-direction: column;
    box-sizing: border-box;
    overflow: hidden; 
}

.header {
    border-bottom: 2px solid #0056b3;
    padding-bottom: 4px;
    margin-bottom: 6px;
}

.header-table {
    width: 100%;
}

.brand h1 {
    color: #0056b3;
    font-size: 18px;
    margin: 0;
    text-transform: uppercase;
}

.info-grid {
    background: #f4f8fb;
    padding: 5px;
    border-radius: 3px;
    margin-bottom: 6px;
}

.label {
    display: inline-block;
    width: 60px;
    font-weight: bold;
    color: #0056b3;
}

table.items {
    width: 100%;
    border-collapse: collapse;
}

table.items th {
    background-color: #0056b3;
    color: #ffffff;
    font-size: 10px;
    padding: 5px;
    text-align: left;
}

table.items td {
    padding: 4px 6px;
    border-bottom: 1px solid #eee;
}

.terms {
    margin-top: 8px;
    padding: 6px;
    border: 1px dashed #0056b3;
    border-radius: 3px;
    font-size: 9px;
    background-color: #fcfdfe;
}

.footer-section {
    width: 100%;
    margin-top: auto; 
    padding-top: 5px;
}

.ttd-table {
    width: 100%;
}

.ttd-box {
    text-align: center;
    width: 110px;
    vertical-align: top;
}

.line {
    margin-top: 25px;
    border-top: 1px solid #333;
    padding-top: 2px;
    font-weight: bold;
}

.total-box {
    background: #0056b3;
    color: white;
    padding: 6px 15px;
    border-radius: 4px;
    text-align: right;
    display: inline-block;
}

.total-box h2 {
    margin: 0;
    font-size: 15px;
}

.text-right { text-align: right; }
.text-center { text-align: center; }
</style>

<div class="nota-wrapper">
    <div class="header">
        <table class="header-table">
            <tr>
                <td>
                    <div class="brand">
                        <h1>SPEEDSHOP 2</h1>
                        <p style="margin:0; font-size:9px; font-weight:bold; color:#555;">Handphone • Accessories • Service</p>
                    </div>
                </td>
                <td class="text-right" style="font-size:9px; color:#666;">
                    <strong>Lubuk Linggau</strong><br>
                    Jl. Yos Sudarso No. 45
                </td>
            </tr>
        </table>
    </div>

    <div class="info-grid">
        <table style="width:100%">
            <tr>
                <td width="50%">
                    <div><span class="label">No Nota</span>: #{{ $transaction->id }}</div>
                    <div><span class="label">Tanggal</span>: {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</div>
                </td>
                <td width="50%">
                    <div><span class="label">Customer</span>: {{ $transaction->customer_name }}</div>
                    <div><span class="label">Kasir</span>: {{ $transaction->user ? $transaction->user->name : 'N/A' }}</div>
                </td>
            </tr>
        </table>
        @if(!empty($transaction->description))
            <div style="margin-top: 8px; padding: 8px; background: #f4f8fb; border: 1px solid #d6e4f2; border-radius: 4px; font-size: 10px; color: #333;">
                <strong>Catatan:</strong> {{ $transaction->description }}
            </div>
        @endif
    </div>

    <table class="items">
        <thead>
            <tr>
                <th width="5%" class="text-center">NO</th>
                <th>NAMA BARANG</th>
                <th width="10%" class="text-center">QTY</th>
                <th width="20%" class="text-right">HARGA</th>
                <th width="20%" class="text-right">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->details as $i => $d)
            <tr>
                <td class="text-center">{{ $i+1 }}</td>
                <td>
                    <strong>{{ $d->product->name }}</strong>
                    @if($d->imei1)<div style="font-size:8px; color:#777;">IMEI: {{ $d->imei1 }}</div>@endif
                </td>
                <td class="text-center">{{ $d->quantity }}</td>
                <td class="text-right">Rp {{ number_format($d->harga_final,0,',','.') }}</td>
                <td class="text-right"><strong>Rp {{ number_format($d->quantity * $d->harga_final,0,',','.') }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="terms">
        <strong>PERHATIAN !!!</strong> Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.
        <br><strong>Garansi Tidak Berlaku Apabila:</strong>
        <table style="width:100%; font-size: 8.5px; margin-top: 2px;">
            <tr>
                <td style="padding:0; border:none;">1. Nota hilang / segel rusak.</td>
                <td style="padding:0; border:none;">2. Kena air, jatuh, pecah, salah pakai.</td>
                <td style="padding:0; border:none;">3. LCD Bergaris/berbintik.</td>
            </tr>
        </table>
    </div>

    <div class="footer-section">
        <table class="ttd-table">
            <tr>
                <td class="ttd-box">
                    Pelanggan
                    <div class="line">{{ $transaction->customer_name }}</div>
                </td>
                <td width="20"></td>
                <td class="ttd-box">
                    Hormat Kami
                    <div class="line">Speedshop 2</div>
                </td>
                <td class="text-right" style="vertical-align: bottom;">
                    <div class="total-box">
                        <span style="font-size:9px; display:block;">GRAND TOTAL</span>
                        <h2>Rp {{ number_format($transaction->total,0,',','.') }}</h2>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>