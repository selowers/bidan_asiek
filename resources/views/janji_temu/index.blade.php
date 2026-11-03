@extends('layouts.app')
@section('title', 'Janji Temu')
@section('content')
<div class="appointment-page">
    <div class="appointment-hero d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-4">
        <div>
            <div class="appointment-kicker mb-3"><span class="appointment-kicker-icon"><i class="bi bi-calendar2-week"></i></span> {{ auth()->user()->isAdmin() ? 'Agenda klinik' : 'Konsultasi saya' }}</div>
            <h1 class="display-6 mb-2">{{ auth()->user()->isAdmin() ? 'Kelola Janji Temu' : 'Janji Temu Saya' }}</h1>
            <p class="mb-0">{{ auth()->user()->isAdmin() ? 'Tinjau pengajuan, konfirmasi jadwal, dan pantau tindak lanjut.' : 'Ajukan jadwal, pantau konfirmasi, dan lihat riwayat konsultasi.' }}</p>
        </div>
        <a class="btn appointment-primary-action" href="{{ route('appointments.create') }}"><i class="bi bi-calendar-plus me-2"></i>{{ auth()->user()->isAdmin() ? 'Tambah Janji' : 'Buat Janji Temu' }}</a>
    </div>

    <nav class="appointment-filters mb-4" aria-label="Filter janji temu">
        <div class="nav nav-pills gap-2">
            @foreach ($filters as $value => $label)
                @php($filterUrl = auth()->user()->isAdmin() ? route('appointments.index', ['status' => $value]) : route('appointments.index', ['view' => $value]))
                <a class="nav-link {{ $filter === $value ? 'active' : '' }}" href="{{ $filterUrl }}">
                    {{ $label }}
                    @if (auth()->user()->isAdmin() && $value !== 'all')
                        <span class="ms-1">{{ $counts[$value] ?? 0 }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    </nav>

    <div class="appointment-list card border-0 shadow-sm overflow-hidden">
        <div class="appointment-list-header card-header border-0 p-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                <div class="appointment-list-title">
                    <span class="appointment-list-title-icon"><i class="bi {{ auth()->user()->isAdmin() ? 'bi-list-check' : 'bi-calendar3' }}"></i></span>
                    <div>
                        <h2 class="h5 mb-1">{{ $filters[$filter] }}</h2>
                        <p class="text-muted small mb-0">{{ auth()->user()->isAdmin() ? 'Pilih janji untuk melihat detail atau memperbarui status.' : 'Perubahan jadwal dapat diajukan lebih dari 24 jam sebelum kunjungan.' }}</p>
                    </div>
                </div>
                <span class="appointment-count"><i class="bi bi-calendar-check me-1"></i>{{ $appointments->total() }} janji</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table appointment-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">{{ auth()->user()->isAdmin() ? 'Pasien' : 'Dokter' }}</th>
                        <th>Jadwal</th>
                        <th>{{ auth()->user()->isAdmin() ? 'Dokter / Keperluan' : 'Kebutuhan' }}</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($appointments as $appointment)
                    <tr>
                        <td class="ps-4">
                            <div class="appointment-patient">
                                <span>
                                    @if (auth()->user()->isAdmin())
                                        <a class="fw-semibold d-block" href="{{ route('patients.show', $appointment->patient) }}">{{ $appointment->patient->name }}</a>
                                    @else
                                        <span class="fw-semibold d-block">{{ $appointment->doctor ?: 'Dokter belum ditentukan' }}</span>
                                        <small class="text-muted">{{ $appointment->type ?: 'Kontrol umum' }}</small>
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="appointment-date-block">
                                <span class="appointment-date-day">{{ $appointment->appointment_at->format('d') }}</span>
                                <span>
                                    <strong>{{ $appointment->appointment_at->format('M Y') }}</strong>
                                    <small><i class="bi bi-clock me-1"></i>{{ $appointment->appointment_at->format('H:i') }} WIB</small>
                                </span>
                            </div>
                        </td>
                        <td>
                            @if (auth()->user()->isAdmin())
                                {{ $appointment->doctor ?: 'Dokter belum ditentukan' }}<small class="text-muted d-block">{{ $appointment->service?->name ?? $appointment->type ?? 'Kontrol umum' }}</small>
                            @else
                                {{ $appointment->service?->name ?? $appointment->type ?? 'Kontrol umum' }}
                            @endif
                        </td>
                        <td><span class="appointment-status appointment-status-{{ $appointment->status }}"><i class="bi bi-circle-fill"></i>{{ $statuses[$appointment->status] ?? ucfirst($appointment->status) }}</span></td>
                        <td class="text-end pe-4">
                            <div class="appointment-actions">
                                <a class="appointment-action appointment-action-view" href="{{ route('appointments.show', $appointment) }}" title="Lihat detail"><i class="bi bi-eye"></i></a>
                                @if (auth()->user()->isAdmin() || $appointment->canPatientReschedule())
                                    <a class="appointment-action appointment-action-edit" href="{{ route('appointments.edit', $appointment) }}" title="{{ auth()->user()->isAdmin() ? 'Edit detail' : 'Ajukan perubahan jadwal' }}"><i class="bi bi-pencil"></i></a>
                                @endif
                                @include('janji_temu.status-actions', ['isAdmin' => auth()->user()->isAdmin()])
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-calendar-x display-6 text-muted d-block mb-3"></i>
                            <strong class="d-block">{{ ! auth()->user()->isAdmin() && $filter === 'history' ? 'Belum ada riwayat janji temu' : 'Belum ada janji temu' }}</strong>
                            <span class="text-muted small">{{ auth()->user()->isAdmin() ? ($filter === 'scheduled' ? 'Belum ada pengajuan yang menunggu konfirmasi.' : 'Belum ada janji temu pada kategori ini.') : ($filter === 'history' ? 'Riwayat konsultasi yang selesai atau dibatalkan akan muncul di sini.' : 'Ajukan jadwal konsultasi dengan dokter pilihan Anda.') }}</span>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($appointments->hasPages())
            <div class="p-4 border-top">{{ $appointments->links() }}</div>
        @endif
    </div>
</div>
@endsection
