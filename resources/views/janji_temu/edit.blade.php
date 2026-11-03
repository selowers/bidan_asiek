@extends('layouts.app')
@section('title', 'Edit Janji Temu')
@section('content')
<div class="appointment-page"><div class="appointment-heading mb-4"><div class="appointment-kicker mb-3"><span class="appointment-kicker-icon"><i class="bi bi-pencil-square"></i></span> {{ auth()->user()->isAdmin() ? 'Perbarui agenda' : 'Ajukan perubahan jadwal' }}</div><h1 class="h3 mb-1">{{ auth()->user()->isAdmin() ? 'Edit Janji Temu' : 'Ubah Jadwal Janji Temu' }}</h1><p class="text-muted mb-0">{{ auth()->user()->isAdmin() ? 'Perbarui detail kunjungan pasien.' : 'Jadwal yang diubah akan menunggu konfirmasi admin kembali.' }}</p></div>
<form method="POST" action="{{ route('appointments.update', $appointment) }}" class="appointment-form card border-0 shadow-sm overflow-hidden">@csrf @method('PUT') @include('janji_temu.form')<div class="d-flex justify-content-end gap-2 p-4 pt-0"><a class="btn btn-light" href="{{ route('appointments.index') }}">Batal</a><button class="btn appointment-primary-action"><i class="bi bi-check2 me-2"></i>{{ auth()->user()->isAdmin() ? 'Simpan Perubahan' : 'Kirim Perubahan' }}</button></div></form></div>
@endsection
