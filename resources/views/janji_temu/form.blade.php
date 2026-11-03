@php
    $isPatient = ! auth()->user()?->isAdmin();
    $selectedPatient = $patient ?? ($appointment->patient ?? null);
    $selectedDoctor = old('doctor', $appointment->doctor ?? '');
@endphp

<div class="appointment-form-heading p-4 pb-3 border-bottom">
    <span class="appointment-form-icon"><i class="bi bi-clipboard2-pulse"></i></span>
    <div>
        <h2 class="h5 mb-1">Detail kunjungan</h2>
        <p class="text-muted small mb-0">Isi informasi utama untuk membantu persiapan pemeriksaan.</p>
    </div>
</div>

<div class="p-4">
    <div class="row g-4">
        @if ($isPatient)
            <div class="col-md-6">
                <label class="form-label fw-semibold"><i class="bi bi-person appointment-field-icon appointment-field-icon-coral"></i>Pasien <span class="text-danger">*</span></label>
                @unless (isset($appointment))
                    <input type="hidden" name="patient_id" value="{{ $selectedPatient->id }}">
                @endunless
                <div class="form-control bg-light border-0 text-dark fw-semibold">{{ $selectedPatient->name }}</div>
                <small class="text-muted">Janji dibuat untuk akun pasien Anda.</small>
            </div>
        @else
            <div class="col-md-6">
                <label class="form-label fw-semibold"><i class="bi bi-person appointment-field-icon appointment-field-icon-coral"></i>Pasien <span class="text-danger">*</span></label>
                <select name="patient_id" class="form-select" required>
                    <option value="">Pilih pasien</option>
                    @foreach ($patients as $patientOption)
                        <option value="{{ $patientOption->id }}" @selected((string) old('patient_id', request('patient_id', $appointment->patient_id ?? '')) === (string) $patientOption->id)>{{ $patientOption->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="col-md-6">
            <label class="form-label fw-semibold"><i class="bi bi-card-image appointment-field-icon appointment-field-icon-lime"></i>Layanan <span class="text-danger">*</span></label>
            @if ($isPatient && isset($appointment))
                <div class="form-control bg-light border-0 text-dark fw-semibold">{{ $appointment->service?->name ?? $appointment->type ?? 'Layanan belum ditentukan' }}</div>
            @else
                <select name="service_id" class="form-select" required>
                    <option value="">Pilih layanan</option>
                    @foreach ($services as $serviceOption)
                        <option value="{{ $serviceOption->id }}" @selected((string) old('service_id', request('service_id', $appointment->service_id ?? '')) === (string) $serviceOption->id)>
                            {{ $serviceOption->name }} ({{ $serviceOption->category === 'kia' ? 'KIA' : 'Umum' }}){{ ! $serviceOption->is_active ? ' (Nonaktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            @endif
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold"><i class="bi bi-clock appointment-field-icon appointment-field-icon-amber"></i>Tanggal dan jam <span class="text-danger">*</span></label>
            <input type="datetime-local" name="appointment_at" class="form-control" min="{{ now()->format('Y-m-d\\TH:i') }}" value="{{ old('appointment_at', isset($appointment) && $appointment->appointment_at ? $appointment->appointment_at->format('Y-m-d\\TH:i') : '') }}" required>
            <small class="text-muted">Pilih waktu kontrol pasien.</small>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold"><i class="bi bi-person-badge appointment-field-icon appointment-field-icon-blue"></i>Dokter <span class="text-danger">*</span></label>
            @if ($isPatient && isset($appointment))
                <div class="form-control bg-light border-0 text-dark fw-semibold">{{ $selectedDoctor ?: 'Dokter belum ditentukan' }}</div>
                <small class="text-muted">Dokter tidak berubah saat Anda mengajukan perubahan jadwal.</small>
            @elseif ($isPatient)
                <select name="doctor" class="form-select" required>
                    <option value="">Pilih dokter</option>
                    @foreach ($doctors as $doctorOption)
                        <option value="{{ $doctorOption->name }}" @selected(old('doctor', $selectedDoctor) === $doctorOption->name)>{{ $doctorOption->name }}</option>
                    @endforeach
                </select>
            @else
                <input name="doctor" class="form-control" placeholder="Nama dokter" value="{{ old('doctor', $appointment->doctor ?? '') }}" required>
            @endif
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold"><i class="bi bi-heart-pulse appointment-field-icon appointment-field-icon-coral"></i>Keluhan atau kebutuhan</label>
            <input name="type" class="form-control" placeholder="Contoh: konsultasi rutin" value="{{ old('type', $appointment->type ?? '') }}">
        </div>

        <div class="col-md-12">
            <label class="form-label fw-semibold"><i class="bi bi-chat-left-text appointment-field-icon appointment-field-icon-blue"></i>Catatan</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Tambahkan catatan khusus untuk dokter...">{{ old('notes', $appointment->notes ?? '') }}</textarea>
        </div>
    </div>
    @if ($isPatient)
        <p class="alert alert-info mt-4 mb-0"><i class="bi bi-info-circle me-2"></i>Pengajuan akan berstatus menunggu konfirmasi. Perubahan jadwal mengirim ulang pengajuan kepada admin.</p>
    @endif
</div>
