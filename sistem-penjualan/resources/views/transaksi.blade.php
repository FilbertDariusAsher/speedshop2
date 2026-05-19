@extends('layouts.app')

@section('title', 'Transaksi Penjualan')

@section('content')
<div class="container py-4">

    {{-- NOTIF --}}
    @if(session('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index:9999">
        <div class="toast show text-bg-success border-0">
            <div class="d-flex">
                <div class="toast-body">
                    ✅ {{ session('success') }}
                </div>
                <button class="btn-close btn-close-white m-auto me-2"></button>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index:9999">
        <div class="toast show text-bg-danger border-0">
            <div class="d-flex">
                <div class="toast-body">
                    ❌ {{ session('error') }}
                </div>
                <button class="btn-close btn-close-white m-auto me-2"></button>
            </div>
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ================= UI ASLI KAMU ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Transaksi Penjualan</h3>
            <p class="text-secondary mb-0">Input data penjualan barang dan nomor IMEI.</p>
        </div>
        <button type="button" class="btn btn-primary shadow-sm px-4" id="addItem" style="border-radius: 10px;">
            <i class="bi bi-plus-circle me-2"></i>Tambah Item
        </button>
    </div>

    <form method="POST" action="/transaksi">
        @csrf

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-1 text-center d-none d-md-block">
                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 50px; height: 50px;">
                            <i class="bi bi-person-fill fs-4"></i>
                        </div>
                    </div>
                    <div class="col-md-11">
                        <label class="form-label fw-bold text-secondary small text-uppercase">Nama Pelanggan</label>
                        <input type="text" name="customer_name" class="form-control custom-input py-2" placeholder="Masukkan nama pembeli..." required>
                    </div>
                </div>
            </div>
        </div>

        <div id="items-container">
            <div class="item-card card border-0 shadow-sm mb-4" data-index="0" style="border-radius: 15px; border-left: 5px solid #4e73df !important;">
                <div class="card-body p-4">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">PILIH PRODUK</label>
                            <select name="products[0][product_id]" class="form-select custom-input product-select" required>
                                <option value="" selected disabled> Cari Produk </option>
                                @foreach(\App\Models\Product::all() as $p)
                                <option value="{{ $p->id }}"
                                    data-type="{{ $p->type }}"
                                    data-price="{{ $p->price }}"
                                    data-harga-jual="{{ $p->harga_jual ?? $p->price }}"
                                    data-stock="{{ $p->stock }}">
                                    {{ $p->name }} (Stok: {{ $p->stock }})
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted stock-info">Stok tersedia: -</div>
                        </div>

                        <div class="col-md-1">
                            <label class="form-label fw-semibold small">QTY</label>
                            <input type="number" name="products[0][quantity]" class="form-control custom-input qty-input text-center" value="1" min="1">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted">MODAL (PER UNIT)</label>
                            <input type="number" name="products[0][price_per_unit]" class="form-control custom-input bg-light price-beli" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-primary">HARGA JUAL</label>
                            <input type="number" name="products[0][harga_jual]" class="form-control custom-input harga-jual" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-success">Harga Final (per unit)</label>
                            <input type="number" name="products[0][harga_final]" class="form-control custom-input harga-final" step="0.01" min="0">
                        </div>

                        <div class="col-md-1 d-flex align-items-end justify-content-center">
                            <button type="button" class="btn btn-outline-danger remove-item border-0">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </div>

                    <div class="imei-fields mt-4 p-3 bg-light rounded-4" style="display:none;">
                        <div class="imei-container row g-2"></div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" id="submitBtn" class="btn btn-success px-5 py-2 fw-bold shadow-sm" style="border-radius: 10px;">
            Simpan
        </button>
    </form>
</div>

<style>
.custom-input {
    border: 1px solid #e0e0e0;
    border-radius: 10px;
}
</style>

<script>
let itemIndex = 1;

// TAMBAH ITEM
document.getElementById('addItem').addEventListener('click', function() {
    const container = document.getElementById('items-container');
    const firstItem = document.querySelector('.item-card');
    const newItem = firstItem.cloneNode(true);

    newItem.setAttribute('data-index', itemIndex);

    newItem.querySelectorAll('input, select').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, '[' + itemIndex + ']');
        el.value = el.classList.contains('qty-input') ? 1 : '';
    });

    newItem.querySelector('.imei-container').innerHTML = '';
    newItem.querySelector('.imei-fields').style.display = 'none';

    container.appendChild(newItem);
    itemIndex++;
});

// HAPUS ITEM
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-item')) {
        if (document.querySelectorAll('.item-card').length > 1) {
            e.target.closest('.item-card').remove();
        }
    }
});

// FIX AUTO HARGA (INI YANG TADI RUSAK)
document.addEventListener('change', function(e) {

    if (e.target.classList.contains('product-select')) {
        const item = e.target.closest('.item-card');
        const selected = e.target.options[e.target.selectedIndex];

        const type = selected.dataset.type;
        const price = Number(selected.dataset.price || 0);
        const hargaJual = Number(selected.dataset.hargaJual || 0);
        const stock = selected.dataset.stock || 0;

        const stockInfo = item.querySelector('.stock-info');
        if (stockInfo) {
            stockInfo.textContent = 'Stok tersedia: ' + stock;
        }

        item.querySelector('.price-beli').value = price;
        item.querySelector('.harga-jual').value = hargaJual;
        const hargaFinalInput = item.querySelector('.harga-final');
        if (hargaFinalInput) {
            hargaFinalInput.value = hargaJual;
        }

        if (type === 'HP') {
            item.querySelector('.imei-fields').style.display = 'block';
            generateIMEI(item);
        } else {
            item.querySelector('.imei-fields').style.display = 'none';
            item.querySelector('.imei-container').innerHTML = '';
        }
    }

    if (e.target.classList.contains('qty-input')) {
        const item = e.target.closest('.item-card');
        const select = item.querySelector('.product-select');
        const selected = select.options[select.selectedIndex];

        if (selected && selected.dataset.type === 'HP') {
            generateIMEI(item);
        }
    }
});

function generateIMEI(item) {
    const qty = item.querySelector('.qty-input').value;
    const container = item.querySelector('.imei-container');
    const index = item.getAttribute('data-index');

    container.innerHTML = '';

    for (let i = 1; i <= qty; i++) {
        container.innerHTML += `
        <div class="col-md-6">
            <input type="text" name="products[${index}][imei][${i}][imei1]" class="form-control mb-1" placeholder="IMEI 1" required>
            <input type="text" name="products[${index}][imei][${i}][imei2]" class="form-control mb-2" placeholder="IMEI 2">
        </div>`;
    }
}

// AUTO HILANG NOTIF
setTimeout(() => {
    document.querySelectorAll('.toast').forEach(el => el.remove());
}, 3000);

// LOADING BUTTON
document.querySelector('form').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = 'Menyimpan...';
    btn.disabled = true;
});
</script>

@endsection