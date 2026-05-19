@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-start">
                        <div>
                            <h3 class="fw-bold mb-1">Kelola User</h3>
                            <p class="text-muted mb-0">Buat akun User baru, atur status aktif/nonaktif, dan pantau tanggal pembuatan.</p>
                        </div>
                        <div class="text-md-end">
                            <span class="badge bg-primary rounded-pill py-2 px-3">Total akun: {{ $users->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-x-circle-fill me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row g-4">
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-gradient-primary text-white py-3 px-4" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-person-plus me-2"></i>Tambah User Baru</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.karyawan.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control rounded-4" value="{{ old('name') }}" required>
                            @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control rounded-4" value="{{ old('email') }}" required>
                            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" class="form-control rounded-4" required>
                                @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control rounded-4" required>
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label class="form-label fw-semibold">Role</label>
                            <select name="role" class="form-select rounded-4" required>
                                <option value="karyawan" {{ old('role') === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                <option value="owner" {{ old('role') === 'owner' ? 'selected' : '' }}>Owner</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2">Buat Akun</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-gradient-secondary text-white py-3 px-4" style="background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-people-fill me-2"></i>Daftar Akun Karyawan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-bottom-0">Nama</th>
                                    <th class="border-bottom-0">Email</th>
                                    <th class="border-bottom-0">Role</th>
                                    <th class="border-bottom-0">Status</th>
                                    <th class="border-bottom-0">Dibuat</th>
                                    <th class="text-end border-bottom-0">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td class="fw-semibold">{{ $user->name }}</td>
                                        <td class="text-secondary">{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ in_array($user->role, ['admin','owner']) ? 'dark' : 'info' }} text-white text-uppercase">{{ in_array($user->role, ['admin','owner']) ? 'Owner' : ucfirst($user->role) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $user->active ? 'success' : 'secondary' }}">{{ $user->active ? 'Aktif' : 'Nonaktif' }}</span>
                                        </td>
                                        <td>{{ $user->created_at->format('d M Y') }}</td>
                                        <td class="text-end">
                                            @if($user->id !== auth()->id())
                                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                                    <form method="POST" action="{{ route('admin.karyawan.status', $user->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-outline-{{ $user->active ? 'warning' : 'success' }} rounded-pill">
                                                            {{ $user->active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.karyawan.destroy', $user->id) }}" class="confirm-delete-form" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger rounded-pill confirm-delete-button" data-delete-title="Akun {{ $user->name }}">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-muted small">Akun Anda</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-secondary py-4">Belum ada akun karyawan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteConfirmModalEmployee" tabindex="-1" aria-labelledby="deleteConfirmModalEmployeeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-sm">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="deleteConfirmModalEmployeeLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="deleteConfirmMessageEmployee" class="mb-0">Apakah Anda yakin ingin menghapus akun ini?</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger rounded-pill" id="confirmDeleteButtonEmployee">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let deleteFormEmployee = null;
        const deleteModalEmployee = new bootstrap.Modal(document.getElementById('deleteConfirmModalEmployee'));
        const deleteMessageEmployee = document.getElementById('deleteConfirmMessageEmployee');

        document.querySelectorAll('.confirm-delete-button').forEach(button => {
            button.addEventListener('click', function () {
                deleteFormEmployee = this.closest('.confirm-delete-form');
                const title = this.dataset.deleteTitle || 'Akun ini';
                deleteMessageEmployee.textContent = 'Yakin hapus ' + title + '?';
                deleteModalEmployee.show();
            });
        });

        document.getElementById('confirmDeleteButtonEmployee').addEventListener('click', function () {
            if (deleteFormEmployee) {
                deleteFormEmployee.submit();
            }
        });
    });
</script>
@endsection
