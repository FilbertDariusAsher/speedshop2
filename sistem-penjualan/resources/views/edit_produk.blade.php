@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3>Edit Produk</h3>
            <p class="text-muted">Perbarui data produk.</p>
        </div>
        <a href="/produk" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="/produk/{{ $product->id }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Produk</label>
                        <select name="type" class="form-select" required>
                            <option value="HP" {{ $product->type == 'HP' ? 'selected' : '' }}>HP (Handphone)</option>
                            <option value="Aksesoris" {{ $product->type == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok Saat Ini</label>
                        <input type="text" class="form-control" value="{{ $product->stock }}" readonly>
                        <small class="text-muted">Stok hanya dapat ditambahkan melalui upload faktur</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga Beli (dari Supplier)</label>
                        <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ $product->price }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga Jual (Normal Toko)</label>
                        <input type="number" name="harga_jual" class="form-control" step="0.01" min="0" value="{{ $product->harga_jual ?? '' }}" placeholder="Harga jual ke pelanggan">
                        <small class="text-muted">Kosongkan jika sama dengan harga beli</small>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
@endsection