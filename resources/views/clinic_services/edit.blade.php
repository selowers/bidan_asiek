@extends('layouts.app')
@section('title', 'Edit Layanan Katalog')
@section('content')
<div class="patient-page">
    <div class="patient-heading mb-4"><div class="patient-kicker mb-3"><span><i class="bi bi-pencil-square"></i></span> Katalog klinik</div><h1 class="h3 mb-1">Edit Layanan</h1><p class="text-muted mb-0">Perbarui informasi, gambar, atau status layanan.</p></div>
    <form method="POST" action="{{ route('admin.clinic-services.update', $service) }}" enctype="multipart/form-data" class="patient-form card border-0 shadow-sm overflow-hidden">
        @csrf
        @method('PUT')
        @include('clinic_services.form')
        <div class="d-flex justify-content-end gap-2 p-4 pt-0"><a class="btn btn-light" href="{{ route('admin.clinic-services.index') }}">Batal</a><button class="btn patient-primary-action"><i class="bi bi-check2 me-2"></i>Simpan Perubahan</button></div>
    </form>
</div>
@endsection