@extends('layouts.app')
@section('title', 'Tambah Layanan Katalog')
@section('content')
<div class="patient-page">
    <div class="patient-heading mb-4"><div class="patient-kicker mb-3"><span><i class="bi bi-plus-square"></i></span> Katalog klinik</div><h1 class="h3 mb-1">Tambah Layanan</h1><p class="text-muted mb-0">Tambahkan informasi dan gambar layanan untuk pasien.</p></div>
    <form method="POST" action="{{ route('admin.clinic-services.store') }}" enctype="multipart/form-data" class="patient-form card border-0 shadow-sm overflow-hidden">
        @csrf
        @include('clinic_services.form')
        <div class="d-flex justify-content-end gap-2 p-4 pt-0"><a class="btn btn-light" href="{{ route('admin.clinic-services.index') }}">Batal</a><button class="btn patient-primary-action"><i class="bi bi-check2 me-2"></i>Simpan Layanan</button></div>
    </form>
</div>
@endsection