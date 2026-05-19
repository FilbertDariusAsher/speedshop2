@extends('layouts.app')

@section('title', 'Stok Produk')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Stok Produk</h3>
        </div>
        <div class="text-end">
            <span class="badge bg-white text-dark border shadow-sm px-3 py-2" style="border-radius: 8px;">
                Total: {{ $products->count() }} Produk
            </span>
        </div>
    </div>

    <!-- Search Form -->
    <form method="GET" action="/stok" class="mb-4">
        <div class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Cari nama produk..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="type" class="form-control">
                    <option value="">Semua Tipe</option>
                    <option value="HP" {{ request('type') == 'HP' ? 'selected' : '' }}>HP</option>
                    <option value="Aksesoris" {{ request('type') == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Cari</button>
            </div>
        </div>
    </form>

    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-secondary small fw-bold">Nama Produk</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold text-center">Jenis</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold text-center">Stok</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary-subtle text-primary rounded-3 p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                    <i class="bi bi-box-seam fs-4"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark fs-5">{{ $product->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill bg-light text-secondary border px-3 py-2 fw-normal">
                                {{ $product->type }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-block">
                                <span class="fs-4 fw-bold {{ $product->stock <= 5 ? 'text-danger' : 'text-dark' }}">
                                    {{ $product->stock }}
                                </span>
                                <span class="text-muted">Unit</span>
                                @if($product->stock <= 5)
                                    <div class="text-danger fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">STOK MENIPIS!</div>
                                    @elseif($product->stock == 0)
                                    <div class="text-danger fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">STOK HABIS!</div>
                                    @else
                                    <div class="text-success fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">STOK CUKUP</div>
                                @endif
                            </div>
                        </td>
                        <td class="text-center">
                            @if($product->has_invoice)
                                <span class="btn btn-sm btn-outline-success disabled opacity-100 border-success-subtle bg-success-subtle text-success px-3 py-1" style="border-radius: 10px; font-weight: 500;">
                                    <i class="bi bi-check-circle-fill me-1"></i> Terverifikasi
                                </span>
                            @else
                                <span class="btn btn-sm btn-outline-warning disabled opacity-100 border-warning-subtle bg-warning-subtle text-warning px-3 py-1" style="border-radius: 10px; font-weight: 500;">
                                    <i class="bi bi-exclamation-circle-fill me-1"></i> Belum Upload
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-secondary">Belum ada data produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .bg-primary-subtle { background-color: #eef2ff !important; }
    .bg-success-subtle { background-color: #ecfdf5 !important; }
    .bg-warning-subtle { background-color: #fffbeb !important; }
    
    .table thead th {
        border-bottom: none;
        font-size: 0.75rem;
        letter-spacing: 0.05rem;
    }

    .table tbody tr {
        transition: transform 0.2s;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa !important;
    }
</style>
@endsection