@extends('layouts.app')

@section('title', 'Pembelian')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index:9999">
        <div class="toast show text-bg-success border-0">
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index:9999">
        <div class="toast show text-bg-danger border-0">
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Riwayat Pembelian Stok</h3>
            <p class="text-secondary mb-0">Manajemen pembelian stok, faktur, dan pemasukan barang.</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary px-4 py-2 shadow-sm" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="bi bi-plus-lg me-2"></i>Upload Faktur Baru
            </button>
        </div>
    </div>

    <!-- Search Form -->
    <form method="GET" action="/faktur-pembelian" class="mb-4">
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

    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-secondary small fw-bold">Tanggal</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Produk</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Jenis</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Harga Beli</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Harga Jual</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold text-center">Stok Ditambahkan</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold text-center">File</th>
                        <th class="pe-4 py-3 text-uppercase text-secondary small fw-bold">Diupload Oleh</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                    <tr>
                        <td class="ps-4">
                            <span class="text-dark fw-medium">{{ $invoice->created_at->format('d M Y') }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $invoice->product->name }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border fw-normal">{{ $invoice->product->type }}</span>
                        </td>
                        <td>
                            <span class="fw-bold text-dark">Rp {{ number_format($invoice->harga_beli ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            <span class="fw-bold text-dark">Rp {{ number_format($invoice->product->harga_jual ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill bg-success-subtle text-success px-3" style="font-size: 0.9rem;">
                                +{{ $invoice->stock_amount }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($invoice->invoice_file && file_exists(public_path($invoice->invoice_file)))
                                <a href="{{ route('invoice.download', $invoice->id) }}" target="_blank" class="btn btn-sm btn-outline-primary border-0 bg-light-subtle">
                                    <i class="bi bi-eye-fill me-1"></i> Lihat
                                </a>
                            @else
                                <span class="text-secondary small">Tidak tersedia</span>
                            @endif
                        </td>
                        <td class="pe-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2 bg-secondary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 24px; height: 24px; font-size: 10px;">
                                    {{ strtoupper(substr($invoice->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="small text-muted">{{ $invoice->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-warning border-0" title="Edit Pembelian" 
                                data-bs-toggle="modal" data-bs-target="#editModal" 
                                data-invoice-id="{{ $invoice->id }}"
                                data-stock-amount="{{ $invoice->stock_amount }}"
                                data-harga-beli="{{ $invoice->harga_beli ?? 0 }}"
                                data-harga-jual="{{ $invoice->product->harga_jual ?? 0 }}">
                                <i class="bi bi-pencil-fill"></i> Edit
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="empty" style="width: 80px; opacity: 0.5;">
                            <p class="mt-3 text-secondary">Belum ada faktur yang diupload</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Upload Faktur -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Faktur Pembelian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="/upload-faktur" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Pilih Produk</label>
                                <select id="productSelect" name="product_id" class="form-select" required>
                                    <option value="">Pilih produk</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}"
                                            data-price="{{ $product->price }}"
                                            data-harga-jual="{{ $product->harga_jual ?? $product->price }}"
                                            data-stock="{{ $product->stock }}">
                                            {{ $product->name }} - {{ $product->type }} (Stok: {{ $product->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Stok Sekarang</label>
                                <input type="text" id="currentStock" class="form-control" readonly value="-">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Harga Beli Saat Ini</label>
                                <input type="text" id="currentPrice" class="form-control" readonly value="-">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Harga Jual Saat Ini</label>
                                <input type="text" id="currentHargaJual" class="form-control" readonly value="-">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Stok Ditambahkan</label>
                                <input type="number" name="additional_stock" class="form-control" min="1" value="1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Harga Beli Baru (opsional)</label>
                                <input type="number" name="new_harga_beli" class="form-control" min="0" step="0.01" placeholder="Kosongkan jika tidak berubah">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Harga Jual Baru (opsional)</label>
                                <input type="number" name="new_harga_jual" class="form-control" min="0" step="0.01" placeholder="Kosongkan jika tidak berubah">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">File Faktur</label>
                                <input type="file" name="invoice_file" class="form-control" accept="image/*,application/pdf" required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Upload Faktur</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatCurrency(value) {
            return 'Rp ' + parseFloat(value).toLocaleString('id-ID', { minimumFractionDigits: 0 });
        }

        function updateProductPreview() {
            const select = document.getElementById('productSelect');
            const selected = select.options[select.selectedIndex];
            const stock = document.getElementById('currentStock');
            const price = document.getElementById('currentPrice');
            const hargaJual = document.getElementById('currentHargaJual');

            if (selected && selected.value) {
                stock.value = selected.dataset.stock || 0;
                price.value = selected.dataset.price ? formatCurrency(selected.dataset.price) : '-';
                hargaJual.value = selected.dataset.hargaJual ? formatCurrency(selected.dataset.hargaJual) : '-';
            } else {
                stock.value = '-';
                price.value = '-';
                hargaJual.value = '-';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const productSelect = document.getElementById('productSelect');
            productSelect.addEventListener('change', updateProductPreview);
            updateProductPreview();

            const successMsg = document.querySelector('.text-bg-success');
            if (successMsg) {
                setTimeout(() => {
                    const modal = document.getElementById('uploadModal');
                    const uploadModal = bootstrap.Modal.getInstance(modal);
                    if (uploadModal) {
                        uploadModal.hide();
                    }
                }, 1500);
            }

            document.querySelectorAll('.toast').forEach(toast => {
                setTimeout(() => toast.remove(), 3000);
            });

            // EDIT MODAL
            document.querySelectorAll('[data-bs-target="#editModal"]').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('editInvoiceId').value = this.dataset.invoiceId;
                    document.getElementById('editStockAmount').value = this.dataset.stockAmount;
                    document.getElementById('editHargaBeli').value = this.dataset.hargaBeli;
                    document.getElementById('editHargaJual').value = this.dataset.hargaJual;
                });
            });
        });
    </script>

    <!-- MODAL EDIT HARGA -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Harga Pembelian & File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="editForm" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Stok (Jumlah)</label>
                            <input type="number" id="editStockAmount" name="new_stock_amount" class="form-control" min="0" placeholder="Biarkan kosong jika tidak ingin mengubah stok">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Harga Beli Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">Rp</span>
                                <input type="number" id="editHargaBeli" name="new_harga_beli" class="form-control border-start-0" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Harga Jual Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">Rp</span>
                                <input type="number" id="editHargaJual" name="new_harga_jual" class="form-control border-start-0" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ganti File Faktur (Opsional)</label>
                            <input type="file" name="invoice_file" class="form-control" accept="image/*,application/pdf" placeholder="Pilih file baru">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti file. Format: JPG, PNG, PDF (Max 2MB)</small>
                        </div>
                        <small class="text-muted">Isi field yang ingin diperbarui saja.</small>
                        <input type="hidden" id="editInvoiceId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const invoiceId = document.getElementById('editInvoiceId').value;
            this.action = '/invoice/' + invoiceId;
            this.submit();
        });
    </script>
</div>

<style>
    .table thead th {
        border-top: none;
        letter-spacing: 0.5px;
    }
    .table tbody tr {
        transition: background-color 0.2s ease-in-out;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa !important;
    }
    .bg-success-subtle {
        background-color: #e8f5e9 !important;
    }
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }
</style>
@endsection