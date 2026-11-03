@extends('layouts.app')
@section('title', $product->title)
@section('content')
<div class="store-page store-narrow"><div class="store-heading"><a class="store-back-link" href="{{ route('store.index') }}"><i class="bi bi-arrow-left me-1"></i>Kembali ke Store</a><span class="store-kicker d-block mt-3"><i class="bi bi-play-circle"></i> Video saya</span><h1>{{ $product->title }}</h1><p>Akses aktif sampai {{ $access->expired_at->format('d M Y, H:i') }} WIB.</p></div><div class="video-player-card card"><video class="video-player" controls controlsList="nodownload" preload="metadata" disablePictureInPicture playsinline oncontextmenu="return false;"><source src="{{ route('videos.stream', $product) }}" type="video/mp4">Browser Anda belum mendukung pemutar video.</video><h2 class="h5 mt-4">{{ $product->title }}</h2><p class="text-muted mb-0">{{ $product->description }}</p></div></div>
@endsection
