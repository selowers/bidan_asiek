@extends('layouts.app')
@section('title', 'Kelola Katalog Layanan')
@section('content')
<div class="patient-page">
    <div class="patient-hero d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-4">
        <div>
            <div class="patient-kicker mb-3"><span><i class="bi bi-images"></i></span> Layanan klinik</div>
            <h1 class="display-6 mb-2">Katalog Layanan</h1>
            <p class="mb-0">Atur layanan Umum dan KIA yang dapat dipilih pasien.</p>
        </div>
        <a class="btn patient-primary-action" href="{{ route('admin.clinic-services.create') }}"><i class="bi bi-plus-lg me-2"></i>Tambah Layanan</a>
    </div>

    <div class="patient-list card border-0 shadow-sm overflow-hidden">
        <div class="patient-list-header card-header border-0 p-4">
            <div class="patient-list-title">
                <span class="patient-list-title-icon"><i class="bi bi-card-image"></i></span>
                <div><h2 class="h5 mb-1">Daftar layanan</h2><p class="text-muted small mb-0">Layanan nonaktif tetap tersimpan untuk riwayat janji temu.</p></div>
            </div>
            <span class="patient-count">{{ $services->total() }} layanan</span>
        </div>
        <div class="table-responsive">
            <table class="table patient-table align-middle mb-0">
                <thead><tr><th class="ps-4">Layanan</th><th>Kategori</th><th>Durasi</th><th>Status</th><th>Janji</th><th class="text-end pe-4">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($services as $service)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img class="catalog-admin-thumb" src="{{ asset('storage/'.$service->image_path) }}" alt="">
                                    <div><a class="fw-semibold text-decoration-none" href="{{ route('admin.clinic-services.edit', $service) }}">{{ $service->name }}</a><small class="d-block text-muted">{{ \Illuminate\Support\Str::limit($service->description, 75) }}</small></div>
                                </div>
                            </td>
                            <td><span class="catalog-category catalog-category-{{ $service->category }}">{{ $service->category === 'kia' ? 'KIA' : 'Umum' }}</span></td>
                            <td>{{ $service->duration_minutes ? $service->duration_minutes.' menit' : 'Tidak ditentukan' }}</td>
                            <td><span class="badge {{ $service->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $service->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td>{{ $service->appointments_count }}</td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex align-items-center gap-2">
                                    <a class="patient-action patient-action-edit" href="{{ route('admin.clinic-services.edit', $service) }}" title="Edit layanan"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('admin.clinic-services.toggle', $service) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm {{ $service->is_active ? 'btn-outline-secondary' : 'btn-outline-success' }}">{{ $service->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5"><i class="bi bi-card-image display-6 text-muted d-block mb-3"></i><strong class="d-block">Katalog masih kosong</strong><span class="text-muted small">Tambahkan layanan beserta gambar agar dapat dipilih pasien.</span></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($services->hasPages())<div class="p-4 border-top">{{ $services->links() }}</div>@endif
    </div>
</div>
@endsection