@extends('layouts.app')
@section('title', 'Edit Akun Pengguna')
@section('content')
<div class="store-page store-narrow user-management-page">
    <div class="store-heading">
        <span class="store-kicker"><i class="bi bi-pencil-square"></i> Edit akun</span>
        <h1>Perbarui Pengguna</h1>
        <p>Ubah data akun pengguna dan akses sistem sesuai kebutuhan klinik.</p>
    </div>

    <form method="POST" action="{{ route('users.update', $user) }}" class="user-form card">
        @csrf
        @method('PUT')

        <div class="user-form-intro">
            <span class="user-form-icon"><i class="bi bi-shield-check"></i></span>
            <div>
                <h2 class="h5 mb-1">Identitas dan akses</h2>
                <p class="text-muted small mb-0">Perbarui tipe akun, email, dan profil pengguna.</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama lengkap <span class="text-danger">*</span></label>
                <input name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Email login <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Password baru</label>
                <input type="password" name="password" class="form-control" minlength="8" placeholder="Kosongkan jika tidak ingin mengubah">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Konfirmasi password</label>
                <input type="password" name="password_confirmation" class="form-control" minlength="8" placeholder="Konfirmasi password baru">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Jenis akun <span class="text-danger">*</span></label>
                <div class="account-type-grid">
                    <label class="account-type-card">
                        <input type="radio" name="account_type" value="patient" @checked(old('account_type', $user->isAdmin() ? 'nurse' : 'patient') === 'patient')>
                        <span>
                            <i class="bi bi-person-heart"></i>
                            <strong>Pasien</strong>
                            <small>Memiliki profil dan hanya melihat datanya sendiri.</small>
                        </span>
                    </label>
                    <label class="account-type-card">
                        <input type="radio" name="account_type" value="nurse" @checked(old('account_type', $user->isAdmin() ? 'nurse' : 'patient') === 'nurse')>
                        <span>
                            <i class="bi bi-person-badge"></i>
                            <strong>Perawat</strong>
                            <small>Mendapat akses admin untuk mengelola klinik.</small>
                        </span>
                    </label>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Nomor telepon pasien</label>
                <input name="phone" class="form-control" value="{{ old('phone', $user->patient?->phone) }}" placeholder="08xxxxxxxxxx">
                <small class="text-muted">Diisi untuk akun pasien. Profil pasien akan disesuaikan otomatis.</small>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a class="btn btn-light" href="{{ route('users.index') }}">Batal</a>
            <button class="btn store-primary"><i class="bi bi-check2-circle me-2"></i>Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
