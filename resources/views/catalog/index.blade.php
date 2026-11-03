@extends('layouts.app')
@section('title', 'Katalog Layanan')
@section('content')
<div class="catalog-page">
    <div class="catalog-hero mb-4">
        <div class="catalog-hero-content">
            <span class="catalog-eyebrow"><i class="bi bi-heart-pulse me-2"></i>Layanan Bidan Asiek</span>
            <h1 class="h2 mb-2">Katalog Layanan</h1>
            <p class="mb-0">Pilih layanan yang sesuai dengan kebutuhan kunjungan Anda.</p>
        </div>
        <div class="catalog-hero-mark" aria-hidden="true"><i class="bi bi-clipboard2-pulse"></i></div>
    </div>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div class="nav nav-pills gap-2" aria-label="Kategori layanan">
            @foreach ($categories as $value => $label)
                <a class="nav-link {{ $category === $value ? 'active' : '' }}" href="{{ route('catalog.index', ['category' => $value]) }}">{{ $label }}</a>
            @endforeach
        </div>
        <a class="btn btn-outline-success" href="{{ route('appointments.index') }}"><i class="bi bi-calendar2-check me-2"></i>Janji temu saya</a>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-4">
        @forelse ($services as $service)
            <div class="col">
                <article class="catalog-service-card h-100">
                    <div class="catalog-service-image-wrap">
                        <img class="catalog-service-image" src="{{ asset('storage/'.$service->image_path) }}" alt="{{ $service->name }}">
                        <span class="catalog-category catalog-category-{{ $service->category }}">{{ $service->category === 'kia' ? 'KIA' : 'Umum' }}</span>
                    </div>
                    <div class="catalog-service-body">
                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <h2 class="h5 mb-0">{{ $service->name }}</h2>
                            @if ($service->duration_minutes)
                                <span class="catalog-duration"><i class="bi bi-clock me-1"></i>{{ $service->duration_minutes }} mnt</span>
                            @endif
                        </div>
                        <p class="catalog-service-description">{{ $service->description ?: 'Informasi layanan dapat ditanyakan saat kunjungan.' }}</p>
                        <a class="btn appointment-primary-action w-100" href="{{ route('appointments.create', ['service_id' => $service->id]) }}"><i class="bi bi-calendar-plus me-2"></i>Ajukan Janji</a>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="catalog-empty-state text-center py-5">
                    <i class="bi bi-images display-5 d-block mb-3"></i>
                    <h2 class="h5">Belum ada layanan pada kategori ini</h2>
                    <p class="mb-0">Silakan periksa kembali nanti atau pilih kategori layanan lainnya.</p>
                </div>
            </div>
        @endforelse
    </div>
    @if ($services->hasPages())<div class="mt-4">{{ $services->links() }}</div>@endif
</div>
@endsection