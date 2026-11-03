@extends('layouts.app')
@section('title', 'Edit Pasien')
@section('content')
<div class="patient-page">
	<div class="patient-heading mb-4">
		<div class="patient-kicker mb-3"><span><i class="bi bi-person-gear"></i></span> Perbarui profil</div>
		<h1 class="h3 mb-1">Edit Pasien</h1>
		<p class="text-muted mb-0">Perbarui informasi pasien agar catatan klinik tetap akurat.</p>
	</div>
	<form method="POST" action="{{ route('patients.update', $patient) }}" class="patient-form card border-0 shadow-sm overflow-hidden">
		@csrf
		@method('PUT')
		@include('pasien.form')
		@include('pasien.relationship-fields')
		<div class="d-flex justify-content-end gap-2 p-4 pt-0">
			<a class="btn btn-light" href="{{ route('patients.show', $patient) }}">Batal</a>
			<button class="btn patient-primary-action"><i class="bi bi-check2 me-2"></i>Simpan Perubahan</button>
		</div>
	</form>
</div>
@endsection
