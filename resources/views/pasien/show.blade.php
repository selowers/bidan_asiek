@extends('layouts.app')
@section('title', $patient->name)
@section('content')
<div class="patient-page">
    <div class="patient-profile-hero d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="patient-profile-identity">
            <div class="patient-profile-avatar-icon" aria-hidden="true">
                <i class="bi bi-person-heart"></i>
            </div>
            <div>
                <div class="patient-kicker mb-2"><span><i class="bi bi-person-heart"></i></span> Profil pasien</div>
                <h1 class="mb-1">{{ $patient->name }}</h1>
                <div class="patient-hero-meta">
                    <span><i class="bi bi-telephone"></i>{{ $patient->phone }}</span>
                    <span><i class="bi bi-envelope"></i>{{ $patient->email ?: 'Email belum diisi' }}</span>
                </div>
                <div class="patient-profile-badges">
                    <span class="patient-profile-badge"><i class="bi bi-shield-check"></i> Pasien aktif</span>
                    <span class="patient-profile-badge"><i class="bi bi-calendar2-week"></i> {{ $patient->appointments->count() }} riwayat</span>
                    <span class="patient-profile-badge"><i class="bi bi-person-check"></i> {{ $patient->gender ? ['male' => 'Laki-laki', 'female' => 'Perempuan', 'other' => 'Lainnya'][$patient->gender] : 'Belum ada data' }}</span>
                </div>
            </div>
        </div>

        @if (auth()->user()?->isAdmin())
            <div class="d-flex gap-2 patient-hero-actions">
                <a class="btn btn-light" href="{{ route('patients.edit', $patient) }}"><i class="bi bi-pencil me-2"></i>Edit</a>
                <a class="btn patient-primary-action" href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}"><i class="bi bi-calendar-plus me-2"></i>Buat Janji</a>
            </div>
        @endif
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="patient-info-card card border-0 shadow-sm p-4">
                <div class="patient-section-heading">
                    <span class="patient-section-icon patient-section-icon-amber"><i class="bi bi-person-vcard"></i></span>
                    <div>
                        <h2 class="h5 mb-1">Informasi pasien</h2>
                        <p class="text-muted small mb-0">Data dasar dan catatan penting.</p>
                    </div>
                </div>

                <dl class="patient-info-list mb-0">
                    <div class="info-row">
                        <dt>Tanggal lahir</dt>
                        <dd>{{ $patient->date_of_birth?->format('d M Y') ?: '-' }}</dd>
                    </div>
                    <div class="info-row">
                        <dt>Kategori pasien</dt>
                        <dd>{{ ['child' => 'Anak-anak', 'female' => 'Perempuan', 'male' => 'Laki-laki'][$patient->patient_category] ?? 'Belum dipilih' }}</dd>
                    </div>
                    <div class="info-row">
                        <dt>Jenis layanan</dt>
                        <dd>{{ ['general' => 'Umum', 'kia' => 'KIA'][$patient->service_category] ?? 'Belum dipilih' }}</dd>
                    </div>
                    <div class="info-row">
                        <dt>Jenis kelamin</dt>
                        <dd>{{ ['male' => 'Laki-laki', 'female' => 'Perempuan', 'other' => 'Lainnya'][$patient->gender] ?? '-' }}</dd>
                    </div>
                    @if ($patient->guardian_name)
                        <div class="info-row"><dt>Nama wali</dt><dd>{{ $patient->guardian_name }}</dd></div>
                    @endif
                    @if ($patient->husband_name)
                        <div class="info-row"><dt>Nama suami</dt><dd>{{ $patient->husband_name }}</dd></div>
                    @endif
                    @if ($patient->wife_name)
                        <div class="info-row"><dt>Nama istri</dt><dd>{{ $patient->wife_name }}</dd></div>
                    @endif
                    <div class="info-row">
                        <dt>Alamat</dt>
                        <dd>{{ $patient->address ?: '-' }}</dd>
                    </div>
                    <div class="info-row">
                        <dt>Catatan medis</dt>
                        <dd>{{ $patient->medical_notes ?: '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="patient-history-card card border-0 shadow-sm p-4">
                <div class="patient-section-heading">
                    <span class="patient-section-icon patient-section-icon-blue"><i class="bi bi-calendar2-heart"></i></span>
                    <div>
                        <h2 class="h5 mb-1">Riwayat janji temu</h2>
                        <p class="text-muted small mb-0">Rekam kunjungan pasien terbaru.</p>
                    </div>
                </div>

                <div class="list-group list-group-flush">
                    @forelse ($patient->appointments->sortByDesc('appointment_at') as $appointment)
                        <a class="patient-history-item list-group-item px-0 text-decoration-none" href="{{ route('appointments.show', $appointment) }}">
                            <span class="patient-history-date">
                                <strong>{{ $appointment->appointment_at->format('d') }}</strong>
                                <small>{{ $appointment->appointment_at->format('M Y') }}</small>
                            </span>
                            <span class="patient-history-content">
                                <strong>{{ $appointment->appointment_at->format('H:i') }} WIB � {{ $appointment->type ?: 'Kontrol umum' }}</strong>
                                <small>{{ $appointment->doctor ?: 'Dokter belum ditentukan' }}</small>
                            </span>
                            <span class="patient-history-status patient-history-status-{{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>
                        </a>
                    @empty
                        <p class="text-muted mb-0">Belum ada riwayat janji temu.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection