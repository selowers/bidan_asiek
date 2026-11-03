@extends('layouts.app')
@section('title', 'Tambah Pasien')
@section('content')
<div class="patient-page">
	<div class="patient-heading mb-4">
		<div class="patient-kicker mb-3"><span><i class="bi bi-person-plus"></i></span> Profil baru</div>
		<h1 class="h3 mb-1">Tambah Pasien</h1>
		<p class="text-muted mb-0">Daftarkan identitas pasien untuk mulai mencatat perawatan.</p>
	</div>
	<form method="POST" action="{{ route('patients.store') }}" class="patient-form card border-0 shadow-sm overflow-hidden">
		@csrf
		@include('pasien.form')
		@include('pasien.relationship-fields')
		<div class="d-flex justify-content-end gap-2 p-4 pt-0">
			<a class="btn btn-light" href="{{ route('patients.index') }}">Batal</a>
			<button class="btn patient-primary-action"><i class="bi bi-check2 me-2"></i>Simpan Pasien</button>
		</div>
	</form>
</div>
@endsection
