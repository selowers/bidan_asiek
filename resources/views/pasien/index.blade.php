@extends('layouts.app')
@section('title', 'Data Pasien')
@section('content')
<div class="patient-page">
	<div class="patient-hero d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-4">
		<div>
			<div class="patient-kicker mb-3"><span><i class="bi bi-heart-pulse"></i></span> Ruang pasien</div>
			<h1 class="display-6 mb-2">Data Pasien</h1>
			<p class="mb-0">Kelola identitas, kontak, dan riwayat perawatan dengan rapi.</p>
		</div>
		<div class="d-flex flex-wrap gap-2">
			<div class="dropdown">
				<button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
					<i class="bi bi-funnel me-2"></i>{{ $categories[$category] }}
				</button>
				<ul class="dropdown-menu dropdown-menu-end">
					@foreach ($categories as $value => $label)
						<li><a class="dropdown-item {{ $category === $value ? 'active' : '' }}" href="{{ route('patients.index', ['category' => $value]) }}">{{ $label }}</a></li>
					@endforeach
				</ul>
			</div>
			<a class="btn patient-primary-action" href="{{ route('patients.create') }}"><i class="bi bi-person-plus me-2"></i>Tambah Pasien</a>
		</div>
	</div>

	<div class="patient-list card border-0 shadow-sm overflow-hidden">
		<div class="patient-list-header card-header border-0 p-4">
			<div class="patient-list-title">
				<span class="patient-list-title-icon"><i class="bi bi-people"></i></span>
				<div>
					<h2 class="h5 mb-1">{{ $categories[$category] }}</h2>
					<p class="text-muted small mb-0">Daftar pasien yang sesuai kategori pilihan.</p>
				</div>
			</div>
			<span class="patient-count"><i class="bi bi-person-check me-1"></i>{{ $patients->total() }} pasien</span>
		</div>

		<div class="table-responsive">
			<table class="table patient-table align-middle mb-0">
				<thead>
					<tr><th class="ps-4">Pasien</th><th>Kontak</th><th>Tanggal Lahir</th><th>Kategori</th><th>Layanan</th><th>Jenis Kelamin</th><th class="text-end pe-4">Aksi</th></tr>
				</thead>
				<tbody>
					@forelse ($patients as $patient)
						<tr>
							<td class="ps-4"><div class="patient-identity"><span><a href="{{ route('patients.show', $patient) }}" class="fw-semibold d-block">{{ $patient->name }}</a></span></div></td>
							<td><div class="patient-contact"><i class="bi bi-telephone"></i><span>{{ $patient->phone }}</span></div><small class="text-muted d-block ms-4">{{ $patient->email ?: 'Email belum diisi' }}</small></td>
							<td><span class="patient-info-pill patient-info-pill-amber"><i class="bi bi-calendar3"></i>{{ $patient->date_of_birth?->format('d M Y') ?: 'Belum diisi' }}</span></td>
							<td>{{ ['child' => 'Anak-anak', 'female' => 'Perempuan', 'male' => 'Laki-laki'][$patient->patient_category] ?? 'Belum dipilih' }}</td>
							<td>{{ ['general' => 'Umum', 'kia' => 'KIA'][$patient->service_category] ?? 'Belum dipilih' }}</td>
							<td><span class="patient-gender patient-gender-{{ $patient->gender ?: 'other' }}"><i class="bi bi-gender-ambiguous"></i>{{ ['male' => 'Laki-laki', 'female' => 'Perempuan', 'other' => 'Lainnya'][$patient->gender] ?? 'Belum diisi' }}</span></td>
							<td class="text-end pe-4">
								<div class="patient-actions">
									<a class="patient-action patient-action-view" href="{{ route('patients.show', $patient) }}" title="Lihat detail"><i class="bi bi-eye"></i></a>
									<a class="patient-action patient-action-edit" href="{{ route('patients.edit', $patient) }}" title="Edit"><i class="bi bi-pencil"></i></a>
									<form class="d-inline" method="POST" action="{{ route('patients.destroy', $patient) }}" onsubmit="return confirm('Hapus data pasien ini?')">@csrf @method('DELETE')<button class="patient-action patient-action-delete" title="Hapus"><i class="bi bi-trash"></i></button></form>
								</div>
							</td>
						</tr>
					@empty
						<tr><td colspan="7" class="text-center py-5"><i class="bi bi-person-x display-6 text-muted d-block mb-3"></i><strong class="d-block">Belum ada data pasien</strong><span class="text-muted small">Tambahkan pasien pertama untuk mulai mengelola data klinik.</span></td></tr>
					@endforelse
				</tbody>
			</table>
		</div>

		@if ($patients->hasPages())
			<div class="p-4 border-top">{{ $patients->links() }}</div>
		@endif
	</div>
</div>
@endsection
