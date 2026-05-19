@extends('layouts.app')

@section('title', 'Upload Faktur')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Upload Faktur Pembelian</h3>
            <p class="text-secondary mb-0">Tambah stok produk secara otomatis melalui unggah berkas.</p>
        </div>
        <a href="/faktur-pembelian" class="btn btn-outline-secondary px-4 shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-clock-history me-2"></i>Riwayat
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 10px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-4">
            <form method="POST" action="/upload-faktur" enctype="multipart/form-data">
                @csrf
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">
                            <i class="bi bi-tag-fill me-1 text-primary"></i> NAMA PRODUK
                        </label>
                        <input type="text" name="product_name" class="form-control custom-input" placeholder="Contoh: Oppo Reno 14F 5G" required>
                        <div class="form-text text-muted">Nama barang sesuai yang tertera di nota.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">
                            <i class="bi bi-grid-fill me-1 text-primary"></i> TIPE PRODUK
                        </label>
                        <select name="product_type" class="form-select custom-input" required>
                            <option value="" selected disabled> Pilih Kategori </option>
                            <option value="HP">HP (Handphone)</option>
                            <option value="Aksesoris">Aksesoris</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">
                            <i class="bi bi-box-seam-fill me-1 text-primary"></i> JUMLAH STOK
                        </label>
                        <input type="number" name="stock_amount" class="form-control custom-input" min="1" placeholder="0" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">
                            <i class="bi bi-cart-plus-fill me-1 text-primary"></i> HARGA BELI 
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">Rp</span>
                            <input type="number" name="harga_beli" class="form-control custom-input border-start-0" step="0.01" min="0" placeholder="Harga dari Faktur" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">
                            <i class="bi bi-cash-stack me-1 text-primary"></i> HARGA JUAL KE CUSTOMER
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">Rp</span>
                            <input type="number" name="harga_jual" class="form-control custom-input border-start-0" step="0.01" min="0" placeholder="" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small">
                            <i class="bi bi-file-earmark-arrow-up-fill me-1 text-primary"></i> BUKTI FAKTUR
                        </label>
                        <input type="file" name="invoice_file" class="form-control custom-input" accept=".jpg,.jpeg,.png,.pdf" required>
                        <div class="form-text text-muted">Format yang didukung: JPG, PNG, atau PDF.</div>
                    </div>
                </div>

                <hr class="my-4 opacity-50">

                <div class="d-flex justify-content-end">
                    <button type="reset" class="btn btn-light px-4 me-2 fw-medium" style="border-radius: 8px;">Reset</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm" style="border-radius: 8px;">
                        <i class="bi bi-cloud-arrow-up me-2"></i>Simpan Data Faktur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .custom-input {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        background-color: #fcfcfc;
        transition: all 0.2s ease;
    }

    .custom-input:focus {
        background-color: #fff;
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.1);
    }

    .input-group-text {
        border-radius: 10px 0 0 10px !important;
        border: 1px solid #e0e0e0;
        color: #6c757d;
        font-weight: 600;
    }

    .btn-primary {
        background-color: #4e73df;
        border: none;
    }

    .btn-primary:hover {
        background-color: #2e59d9;
        transform: translateY(-1px);
    }

    .form-text {
        font-size: 0.8rem;
    }
</style>
@endsection