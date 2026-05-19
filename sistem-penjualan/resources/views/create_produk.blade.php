@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3>Tambah Produk Baru</h3>
            <p class="text-muted">Tambah produk terlebih dahulu sebelum melakukan upload faktur.</p>
        </div>
        <a href="/produk" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form method="POST" action="/produk">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis</label>
                        <select name="type" class="form-select" required>
                            <option value="HP">HP</option>
                            <option value="Aksesoris">Aksesoris</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga Beli</label>
                        <input type="number" name="price" class="form-control" min="0" step="0.01" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Harga Jual</label>
                        <input type="number" name="harga_jual" class="form-control" min="0" step="0.01" required>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4 py-2">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
@endsection
