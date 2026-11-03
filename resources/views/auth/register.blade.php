@extends('layouts.auth')
@section('title', 'Daftar')
@section('content')
<div class="auth-heading"><span class="auth-eyebrow">Akun pasien</span><h2>Buat akun Anda</h2><p>Gunakan email yang sudah terdaftar pada data pasien klinik.</p></div>
<form method="POST" action="{{ route('register.store') }}" class="auth-form">@csrf
    <div class="mb-3"><label class="form-label">Nama lengkap</label><div class="auth-input"><i class="bi bi-person"></i><input name="name" value="{{ old('name') }}" placeholder="Nama sesuai data pasien" required autofocus></div></div>
    <div class="mb-3"><label class="form-label">Email pasien</label><div class="auth-input"><i class="bi bi-envelope"></i><input type="email" name="email" value="{{ old('email') }}" placeholder="Email yang terdaftar" required></div></div>
    <div class="mb-3"><label class="form-label">Password</label><div class="auth-input"><i class="bi bi-lock"></i><input type="password" name="password" placeholder="Minimal 8 karakter" required></div></div>
    <div class="mb-4"><label class="form-label">Konfirmasi password</label><div class="auth-input"><i class="bi bi-check2-circle"></i><input type="password" name="password_confirmation" placeholder="Ulangi password" required></div></div>
    <button class="btn auth-submit w-100"><i class="bi bi-person-check me-2"></i>Buat Akun</button>
</form>
<div class="auth-switch">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></div>
@endsection
