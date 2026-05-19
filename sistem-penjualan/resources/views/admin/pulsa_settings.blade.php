@extends('layouts.app')

@section('title', 'Pengaturan Pulsa')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Pengaturan Harga Pulsa</h3>
            <p class="text-muted">Kelola provider dan nominal pulsa serta profit margin.</p>
        </div>
        <a href="/pulsa-token" class="btn btn-outline-secondary px-4 py-2 shadow-sm rounded-4">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 10px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- PROVIDER SECTION -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white py-3 px-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="fw-bold mb-0"><i class="bi bi-network me-2"></i>Provider Pulsa</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Form Tambah Provider -->
                    <form method="POST" action="{{ route('admin.pulsa.provider.store') }}" class="mb-4">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" name="name" class="form-control" placeholder="Nama provider (Telkomsel, XL, dll)" required>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-plus-lg me-1"></i>Tambah
                            </button>
                        </div>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </form>

                    <!-- Daftar Provider -->
                    <div>
                        <h6 class="fw-semibold text-secondary mb-3">Daftar Provider</h6>
                        @forelse($providers as $provider)
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded-3 mb-2">
                                <span class="fw-medium">{{ $provider->name }}</span>
                                <form method="POST" action="{{ route('admin.pulsa.provider.delete', $provider->id) }}" class="confirm-delete-form" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger confirm-delete-button" data-delete-title="Provider {{ $provider->name }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="alert alert-info mb-0">
                                <small>Belum ada provider. Tambahkan provider terlebih dahulu.</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- NOMINAL SECTION -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-success text-white py-3 px-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <h5 class="fw-bold mb-0"><i class="bi bi-cash me-2"></i>Nominal & Markup</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Form Tambah Nominal -->
                    <form method="POST" action="{{ route('admin.pulsa.nominal.store') }}" class="mb-4">
                        @csrf
                        <div class="row g-2 mb-3">
                            <div class="col-md-5">
                                <select name="provider_id" class="form-control" required>
                                    <option value="">Pilih Provider</option>
                                    @foreach($providers as $provider)
                                        <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="nominal_amount" class="form-control" placeholder="Nominal (10, 20, 50)" required>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success w-100" title="Nominal dalam ribuan: 10 = Rp 10.000">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-12">
                                <input type="number" name="markup" class="form-control" placeholder="Markup (Rp) - misal: 2000" value="0" required>
                            </div>
                        </div>
                    </form>

                    <!-- Tabel Nominal per Provider -->
                    <div>
                        <h6 class="fw-semibold text-secondary mb-3">Daftar Nominal</h6>
                        @foreach($providers as $provider)
                            @if($provider->nominals->count() > 0)
                                <div class="mb-4">
                                    <h6 class="text-primary fw-semibold mb-2">{{ $provider->name }}</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Nominal</th>
                                                    <th>Markup</th>
                                                    <th>Total</th>
                                                    <th width="80">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($provider->nominals as $nominal)
                                                    <tr>
                                                        <td class="fw-semibold">Rp {{ number_format($nominal->nominal_amount * 1000, 0, ',', '.') }}</td>
                                                        <td>+ Rp {{ number_format($nominal->markup, 0, ',', '.') }}</td>
                                                        <td class="fw-bold text-success">Rp {{ number_format($nominal->getTotalPrice(), 0, ',', '.') }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editNominalModal{{ $nominal->id }}" title="Edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <form method="POST" action="{{ route('admin.pulsa.nominal.delete', $nominal->id) }}" class="confirm-delete-form" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-sm btn-danger confirm-delete-button" data-delete-title="Nominal Rp {{ number_format($nominal->nominal_amount * 1000, 0, ',', '.') }}">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>

                                                    <!-- Modal Edit Nominal -->
                                                    <div class="modal fade" id="editNominalModal{{ $nominal->id }}" tabindex="-1">
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-light border-0">
                                                                    <h6 class="modal-title">Edit Nominal</h6>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <form method="POST" action="{{ route('admin.pulsa.nominal.update', $nominal->id) }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label class="form-label fw-semibold">Nominal (ribu)</label>
                                                                            <input type="number" name="nominal_amount" class="form-control" value="{{ $nominal->nominal_amount }}" required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label class="form-label fw-semibold">Markup (Rp)</label>
                                                                            <input type="number" name="markup" class="form-control" value="{{ $nominal->markup }}" required>
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
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
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
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.1);
    }
</style>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteConfirmModalPulsa" tabindex="-1" aria-labelledby="deleteConfirmModalPulsaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalPulsaLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="deleteConfirmMessagePulsa">Apakah Anda yakin ingin menghapus item ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButtonPulsa">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let deleteFormPulsa = null;
        const deleteModalPulsa = new bootstrap.Modal(document.getElementById('deleteConfirmModalPulsa'));
        const deleteMessagePulsa = document.getElementById('deleteConfirmMessagePulsa');

        document.querySelectorAll('.confirm-delete-button').forEach(button => {
            button.addEventListener('click', function () {
                deleteFormPulsa = this.closest('.confirm-delete-form');
                const title = this.dataset.deleteTitle || 'item ini';
                deleteMessagePulsa.textContent = 'Yakin hapus ' + title + '?';
                deleteModalPulsa.show();
            });
        });

        document.getElementById('confirmDeleteButtonPulsa').addEventListener('click', function () {
            if (deleteFormPulsa) {
                deleteFormPulsa.submit();
            }
        });
    });
</script>
@endsection
