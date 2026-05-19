@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Kelola Produk</h2>
            <p class="text-muted">Tambah produk sendiri terlebih dahulu, kemudian gunakan upload faktur untuk menambah stok.</p>
        </div>
        @if(auth()->user()->role == 'karyawan')
            <a href="/produk/create" class="btn btn-primary px-4 py-2 shadow-sm rounded-4">
                <i class="bi bi-plus-lg me-2"></i>Tambah Produk
            </a>
        @endif
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search Form -->
    <form method="GET" action="/produk" class="mb-4">
        <div class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Cari nama produk..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
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

    <!-- Card Table -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Jenis</th>
                            <th>Stok</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="fw-semibold">{{ $product->name }}</td>

                            <td>
                                <span class="badge bg-primary-subtle text-primary px-3 py-2">
                                    {{ $product->type }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-success-subtle text-success px-3 py-2">
                                    {{ $product->stock }}
                                </span>
                            </td>

                            <td class="fw-bold text-dark">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="fw-bold text-dark">
                                Rp {{ number_format($product->harga_jual ?? $product->price, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if(auth()->user()->role == 'karyawan')
                                <a href="/produk/{{ $product->id }}/edit"
                                   class="btn btn-sm btn-outline-warning me-1">
                                    ✏️ Edit
                                </a>

                                <form method="POST"
                                      action="/produk/{{ $product->id }}"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Yakin hapus produk ini?')">
                                        🗑️ Hapus
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada data produk
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
@endsection