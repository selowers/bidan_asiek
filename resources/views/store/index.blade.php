@extends('layouts.app')
@section('title', 'Store Video')
@section('content')
<div class="store-page">
    <div class="store-hero">
        <div><span class="store-kicker"><i class="bi bi-shop"></i> Materi pilihan</span><h1>Belajar bersama Bidan Asiek</h1><p>Akses video edukasi yang membantu perjalanan kehamilan Anda.</p></div>
        <a class="btn store-cart-button" href="{{ route('cart.index') }}"><i class="bi bi-bag-heart me-2"></i>Keranjang</a>
    </div>
    <div class="store-grid">
        @forelse ($products as $product)
            <article class="product-card">
                <div class="product-cover product-cover-{{ $loop->index % 4 }}">
                    @if ($product->thumbnail_url)<img src="{{ $product->thumbnail_url }}" alt="{{ $product->title }}">@else<i class="bi bi-play-circle"></i>@endif
                    <span>{{ $product->access_duration }} hari akses</span>
                </div>
                <div class="p-4"><h2 class="h5 mb-2">{{ $product->title }}</h2><p class="text-muted small product-description">{{ \Illuminate\Support\Str::limit($product->description, 100) }}</p><div class="d-flex justify-content-between align-items-center gap-2"><strong class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</strong>@if ($accesses->contains($product->id))<a class="btn btn-sm store-owned" href="{{ route('videos.show', $product) }}"><i class="bi bi-play-fill me-1"></i>Tonton</a>@else<form method="POST" action="{{ route('cart.store', $product) }}">@csrf<button class="btn btn-sm store-primary"><i class="bi bi-bag-plus me-1"></i>Ke Keranjang</button></form>@endif</div></div>
            </article>
        @empty
            <div class="store-empty"><i class="bi bi-shop-window"></i><h2>Store sedang disiapkan</h2><p>Belum ada video yang tersedia saat ini.</p></div>
        @endforelse
    </div>
    @if ($products->hasPages())<div class="mt-4">{{ $products->links() }}</div>@endif
</div>
@endsection
