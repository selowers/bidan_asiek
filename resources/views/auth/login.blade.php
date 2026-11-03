@extends('layouts.auth')
@section('title', 'Masuk')
@section('content')
<div class="auth-heading"><span class="auth-eyebrow">Selamat datang kembali</span><h2>Masuk ke akun Anda</h2><p>Gunakan email yang terdaftar untuk melanjutkan.</p></div>
<form method="POST" action="{{ route('login.store') }}" class="auth-form">@csrf
    <div class="mb-3"><label class="form-label">Email</label><div class="auth-input"><i class="bi bi-envelope"></i><input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus></div></div>
    <div class="mb-3"><div class="d-flex justify-content-between"><label class="form-label">Password</label><a href="{{ route('password.request') }}" class="auth-link small">Lupa password?</a></div><div class="auth-input"><i class="bi bi-lock"></i><input type="password" name="password" placeholder="Masukkan password" required></div></div>
    <label class="auth-check mb-4"><input type="checkbox" name="remember" value="1"><span>Ingat saya di perangkat ini</span></label>
    <button class="btn auth-submit w-100"><i class="bi bi-arrow-right me-2"></i>Masuk</button>
</form>
<div class="auth-switch">Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai pasien</a></div>
@endsection
