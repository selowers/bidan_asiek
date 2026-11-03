@extends('layouts.app')
@section('title', 'Detail Janji Temu')
@section('content')
@php($isAdmin = auth()->user()->isAdmin())
<div class="appointment-page">
	<div class="appointment-heading d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
		<div>
			<div class="appointment-kicker mb-3"><span class="appointment-kicker-icon"><i class="bi bi-calendar2-check"></i></span> Detail agenda</div>
			<h1 class="h3 mb-1">{{ $isAdmin ? 'Detail Janji Temu' : 'Jadwal Konsultasi' }}</h1>
			<p class="text-muted mb-0">{{ $appointment->appointment_at->format('d M Y, H:i') }} WIB</p>
		</div>
		<div class="d-flex flex-wrap align-items-center gap-2">
			<a class="btn btn-light" href="{{ route('appointments.index') }}"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
			@if ($isAdmin || $appointment->canPatientReschedule())
				<a class="btn appointment-primary-action" href="{{ route('appointments.edit', $appointment) }}"><i class="bi bi-pencil me-2"></i>{{ $isAdmin ? 'Edit' : 'Ubah Jadwal' }}</a>
			@endif
			@include('janji_temu.status-actions', ['isAdmin' => $isAdmin])
		</div>
	</div>

	<div class="row g-4">
		<div class="col-lg-7">
			<div class="appointment-detail-card card border-0 shadow-sm overflow-hidden">
				<div class="appointment-detail-banner">
					<div class="appointment-date-mark"><i class="bi bi-calendar3"></i></div>
					<div>
						<div class="small text-uppercase mb-2">Jadwal kunjungan</div>
						<div class="h4 mb-1">{{ $appointment->appointment_at->format('d M Y') }}</div>
						<div><i class="bi bi-clock me-2"></i>{{ $appointment->appointment_at->format('H:i') }} WIB</div>
					</div>
				</div>
				<div class="p-4">
					<dl class="row mb-0">
						<dt class="col-sm-4 text-muted fw-normal">Pasien</dt>
						<dd class="col-sm-8">
							@if ($isAdmin)
								<a class="fw-semibold" href="{{ route('patients.show', $appointment->patient) }}">{{ $appointment->patient->name }}</a>
							@else
								<span class="fw-semibold">{{ $appointment->patient->name }}</span>
							@endif
						</dd>
						<dt class="col-sm-4 text-muted fw-normal">Dokter</dt>
						<dd class="col-sm-8">{{ $appointment->doctor ?: 'Belum ditentukan' }}</dd>
						<dt class="col-sm-4 text-muted fw-normal">Keperluan</dt>
						<dd class="col-sm-8">{{ $appointment->service?->name ?? $appointment->type ?? 'Kontrol umum' }}</dd>
						<dt class="col-sm-4 text-muted fw-normal">Status</dt>
						<dd class="col-sm-8"><span class="appointment-status appointment-status-{{ $appointment->status }}"><i class="bi bi-circle-fill"></i>{{ $statuses[$appointment->status] ?? ucfirst($appointment->status) }}</span></dd>
					</dl>
				</div>
			</div>
		</div>
		<div class="col-lg-5">
			<div class="appointment-note-card card border-0 shadow-sm p-4 h-100">
				<h2 class="h5 mb-3"><span class="appointment-note-icon"><i class="bi bi-chat-left-text"></i></span>Catatan</h2>
				<p class="text-muted mb-0">{{ $appointment->notes ?: 'Belum ada catatan untuk janji temu ini.' }}</p>
			</div>
		</div>
	</div>
</div>
@endsection
