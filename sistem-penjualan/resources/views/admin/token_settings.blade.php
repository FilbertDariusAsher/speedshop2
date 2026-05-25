@extends('layouts.app')

@section('title', 'Pengaturan Token Listrik')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-column flex-md-row gap-3">
        <div>
            <h3 class="fw-bold">Pengaturan Harga Token Listrik</h3>
            <p class="text-muted">Kelola nominal dan harga jual token listrik.</p>
            <div class="btn-group btn-group-sm" role="group" aria-label="Pilih pengaturan">
                <a href="/admin/pulsa-settings" class="btn btn-outline-secondary">Setting Pulsa</a>
                <a href="/admin/token-settings" class="btn btn-primary">Setting Token</a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 10px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-warning text-white py-3 px-4" style="background: linear-gradient(135deg, #f5af19 0%, #f12711 100%);">
            <h5 class="fw-bold mb-0"><i class="bi bi-lightning-charge me-2"></i>Daftar Nominal Token</h5>
        </div>
        <div class="card-body p-4">
            <!-- Form Tambah Token Nominal -->
            <form method="POST" action="/admin/token-nominal" class="mb-4">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Nominal (Rp)</label>
                        <input type="number" name="nominal_amount" class="form-control" placeholder="300000" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Harga Jual (Rp)</label>
                        <input type="number" name="harga_final" class="form-control" placeholder="300000" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Markup (Rp)</label>
                        <input type="number" name="profit" class="form-control" placeholder="3000" value="0" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-warning w-100 fw-semibold">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Nominal
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tabel Nominal Token -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nominal</th>
                            <th>Harga Jual</th>
                            <th>Markup</th>
                            <th>Total (Harga + Markup)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nominals as $nominal)
                            <tr>
                                <td class="fw-semibold">Rp {{ number_format($nominal->nominal_amount, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($nominal->harga_final, 0, ',', '.') }}</td>
                                <td>+ Rp {{ number_format($nominal->profit, 0, ',', '.') }}</td>
                                <td class="fw-bold text-success">Rp {{ number_format($nominal->harga_final + $nominal->profit, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#editTokenModal{{ $nominal->id }}" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" action="/admin/token-nominal/{{ $nominal->id }}" class="confirm-delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 rounded-pill px-3 confirm-delete-button" data-delete-title="Nominal Rp {{ number_format($nominal->nominal_amount, 0, ',', '.') }}">
                                            <i class="bi bi-trash3"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit Token Nominal -->
                            <div class="modal fade" id="editTokenModal{{ $nominal->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light border-0">
                                            <h6 class="modal-title">Edit Nominal Token</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="/admin/token-nominal/{{ $nominal->id }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Nominal (Rp)</label>
                                                    <input type="number" name="nominal_amount" class="form-control" value="{{ $nominal->nominal_amount }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Harga Jual (Rp)</label>
                                                    <input type="number" name="harga_final" class="form-control" value="{{ $nominal->harga_final }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Markup (Rp)</label>
                                                    <input type="number" name="profit" class="form-control" value="{{ $nominal->profit }}" required>
                                                </div>
                                                <div class="alert alert-info mb-0">
                                                    <small><strong>Total yang akan dibayar customer:</strong> Rp {{ number_format($nominal->harga_final + $nominal->profit, 0, ',', '.') }}</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-secondary py-4">
                                    Belum ada nominal token. <a href="#" onclick="document.querySelector('form').scrollIntoView()">Tambahkan di atas</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
    }
    .form-control:focus, .form-select:focus {
        border-color: #f5af19;
        box-shadow: 0 0 0 0.25rem rgba(245, 175, 25, 0.1);
    }
</style>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteConfirmModalToken" tabindex="-1" aria-labelledby="deleteConfirmModalTokenLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-body p-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; flex-shrink: 0;">
                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="modal-title fw-bold mb-2" id="deleteConfirmModalTokenLabel">Hapus Data</h5>
                        <p class="text-muted mb-3" id="deleteConfirmMessageToken">Yakin ingin menghapus item ini?</p>
                        <div class="alert alert-light border border-danger-subtle rounded-3 py-2 px-3 mb-0 small text-danger">
                            <i class="bi bi-info-circle me-2"></i>Data yang dihapus tidak bisa dikembalikan.
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger rounded-pill px-4" id="confirmDeleteButtonToken">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let deleteFormToken = null;
        const deleteModalToken = new bootstrap.Modal(document.getElementById('deleteConfirmModalToken'));
        const deleteMessageToken = document.getElementById('deleteConfirmMessageToken');

        document.querySelectorAll('.confirm-delete-button').forEach(button => {
            button.addEventListener('click', function () {
                deleteFormToken = this.closest('.confirm-delete-form');
                const title = this.dataset.deleteTitle || 'item ini';
                deleteMessageToken.textContent = 'Yakin hapus ' + title + '?';
                deleteModalToken.show();
            });
        });

        document.getElementById('confirmDeleteButtonToken').addEventListener('click', function () {
            if (deleteFormToken) {
                deleteFormToken.submit();
            }
        });
    });
</script>
@endsection
