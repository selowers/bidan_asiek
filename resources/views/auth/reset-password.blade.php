@extends('layouts.auth')
@section('title', 'Reset Password')
@section('content')
<div class="auth-heading"><span class="auth-eyebrow">Pemulihan akun</span><h2>Buat password baru</h2><p>Pilih password baru yang kuat untuk mengamankan akun Anda.</p></div>
<form method="POST" action="{{ route('password.update') }}" class="auth-form">@csrf<input type="hidden" name="token" value="{{ $token }}">
    <div class="mb-3"><label class="form-label">Email</label><div class="auth-input"><i class="bi bi-envelope"></i><input type="email" name="email" value="{{ old('email') }}" required autofocus></div></div>
    <div class="mb-3"><label class="form-label">Password baru</label><div class="auth-input"><i class="bi bi-lock"></i><input type="password" name="password" placeholder="Minimal 8 karakter" required></div></div>
    <div class="mb-4"><label class="form-label">Konfirmasi password</label><div class="auth-input"><i class="bi bi-check2-circle"></i><input type="password" name="password_confirmation" required></div></div>
    <button class="btn auth-submit w-100"><i class="bi bi-shield-check me-2"></i>Simpan Password</button>
</form>
@endsection
