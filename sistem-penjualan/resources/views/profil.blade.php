@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; font-size: 1.5rem;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="mb-1">{{ auth()->user()->name }}</h4>
                            <p class="text-muted mb-0">{{ ucfirst(auth()->user()->role) }} / {{ auth()->user()->active ? 'Aktif' : 'Nonaktif' }}</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-secondary">Email</label>
                            <input class="form-control" type="text" value="{{ auth()->user()->email }}" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-secondary">Nama Lengkap</label>
                            <input class="form-control" type="text" value="{{ auth()->user()->name }}" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-secondary">Akun Dibuat</label>
                            <input class="form-control" type="text" value="{{ auth()->user()->created_at->format('d M Y H:i') }}" readonly>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <a href="/logout" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
