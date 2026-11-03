@extends('layouts.app')
@section('title', auth()->user()->isAdmin() ? 'Tambah Janji Temu' : 'Buat Janji')
@section('content')
<div class="appointment-page appointment-create-page">
    <div class="appointment-heading mb-4">
        <div class="appointment-kicker mb-3"><span class="appointment-kicker-icon"><i class="bi bi-calendar-plus"></i></span> {{ auth()->user()->isAdmin() ? 'Agenda baru' : 'Reservasi dokter' }}</div>
        <h1 class="h3 mb-1">{{ auth()->user()->isAdmin() ? 'Tambah Janji Temu' : 'Buat Janji dengan Dokter' }}</h1>
        <p class="text-muted mb-0">{{ auth()->user()->isAdmin() ? 'Buat jadwal kontrol dengan detail yang lengkap.' : 'Pilih dokter, tentukan waktu, dan kirim kebutuhan konsultasi Anda.' }}</p>
    </div>

    <div class="appointment-create-layout">
        <aside class="appointment-create-aside">
            <div class="appointment-aside-orbit"><i class="bi {{ auth()->user()->isAdmin() ? 'bi-calendar-heart' : 'bi-heart-pulse' }}"></i></div>
            <div class="appointment-aside-label">{{ auth()->user()->isAdmin() ? 'Rencana kunjungan' : 'Konsultasi klinik' }}</div>
            <h2>{{ auth()->user()->isAdmin() ? 'Jadwal yang terasa lebih teratur.' : 'Dokter siap menerima konsultasi Anda.' }}</h2>
            <p>{{ auth()->user()->isAdmin() ? 'Catat waktu kontrol, dokter, dan kebutuhan pasien dalam satu agenda yang mudah dipantau.' : 'Pilih jadwal yang Anda inginkan. Pengajuan akan dikirim ke admin untuk dikonfirmasi.' }}</p>

            <div class="appointment-steps">
                <div class="appointment-step"><span>01</span><div><strong>{{ auth()->user()->isAdmin() ? 'Pilih pasien' : 'Pilih dokter' }}</strong><small>{{ auth()->user()->isAdmin() ? 'Tentukan pasien yang akan berkunjung.' : 'Pilih dokter yang Anda inginkan.' }}</small></div></div>
                <div class="appointment-step"><span>02</span><div><strong>Atur jadwal</strong><small>Tentukan tanggal dan waktu pemeriksaan.</small></div></div>
                <div class="appointment-step"><span>03</span><div><strong>{{ auth()->user()->isAdmin() ? 'Lengkapi detail' : 'Kirim pengajuan' }}</strong><small>{{ auth()->user()->isAdmin() ? 'Tambahkan kebutuhan dan catatan konsultasi.' : 'Pantau status konfirmasi dari menu Janji Temu Saya.' }}</small></div></div>
            </div>

            <div class="appointment-aside-palette"><span class="palette-dot palette-dot-lime"></span><span class="palette-dot palette-dot-coral"></span><span class="palette-dot palette-dot-amber"></span><span>Siap mengatur agenda hari ini?</span></div>
            <div class="appointment-aside-footer"><i class="bi bi-shield-check"></i><span>Data jadwal tersimpan aman di sistem klinik.</span></div>
        </aside>

        <form method="POST" action="{{ route('appointments.store') }}" class="appointment-form card border-0 shadow-sm overflow-hidden">
            @csrf
            @include('janji_temu.form')
            <div class="d-flex justify-content-end gap-2 p-4 pt-0">
                <a class="btn btn-light" href="{{ route('appointments.index') }}">Batal</a>
                <button class="btn appointment-primary-action"><i class="bi bi-check2 me-2"></i>{{ auth()->user()->isAdmin() ? 'Simpan Janji' : 'Ajukan Janji' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
