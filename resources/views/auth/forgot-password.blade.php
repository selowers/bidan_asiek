@extends('layouts.auth')
@section('title', 'Lupa Password')
@section('content')
<div class="auth-heading"><span class="auth-eyebrow">Pemulihan akun</span><h2>Lupa password?</h2><p>Masukkan email Anda dan kami akan mengirim link untuk membuat password baru.</p></div>
<form method="POST" action="{{ route('password.email') }}" class="auth-form">@csrf
    <div class="mb-4"><label class="form-label">Email</label><div class="auth-input"><i class="bi bi-envelope"></i><input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus></div></div>
    <button class="btn auth-submit w-100"><i class="bi bi-send me-2"></i>Kirim Link Reset</button>
</form>
<div class="auth-switch"><a href="{{ route('login') }}">Kembali ke halaman masuk</a></div>
@endsection
