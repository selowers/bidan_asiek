@extends('layouts.app')
@section('title', 'Keranjang')
@section('content')
<div class="store-page store-narrow">
    <div class="store-heading">
        <span class="store-kicker"><i class="bi bi-bag-heart"></i> E-Keranjang</span>
        <h1>Keranjang Anda</h1>
        <p>Periksa kembali video sebelum membuat pesanan.</p>
    </div>

    @if ($items->isNotEmpty())
        <div class="cart-summary-strip">
            <div class="cart-summary-pill cart-summary-pill-amber">
                <span class="cart-summary-icon"><i class="bi bi-bag-check"></i></span>
                <div>
                    <small>Item</small>
                    <strong>{{ $items->count() }}</strong>
                </div>
            </div>
            <div class="cart-summary-pill cart-summary-pill-mint">
                <span class="cart-summary-icon"><i class="bi bi-clock-history"></i></span>
                <div>
                    <small>Akses</small>
                    <strong>{{ $items->sum('quantity') }} video</strong>
                </div>
            </div>
            <div class="cart-summary-pill cart-summary-pill-blue">
                <span class="cart-summary-icon"><i class="bi bi-wallet2"></i></span>
                <div>
                    <small>Total</small>
                    <strong>Rp {{ number_format($items->sum(fn ($item) => $item->product->price * $item->quantity), 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    @endif

    <div class="card store-cart-card cart-modern-card">
        @forelse ($items as $item)
            <div class="store-cart-item cart-item-modern">
                <div class="cart-item-main">
                    <span class="cart-item-badge"><i class="bi bi-play-circle"></i></span>
                    <div>
                        <h2 class="h6 mb-1">{{ $item->product->title }}</h2>
                        <small class="text-muted">{{ $item->product->access_duration }} hari akses</small>
                    </div>
                </div>

                <div class="cart-item-meta">
                    <div class="cart-price-box">
                        <span>Subtotal</span>
                        <strong>Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</strong>
                    </div>

                    <form method="POST" action="{{ route('cart.destroy', $item) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm cart-remove-btn" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="store-empty cart-empty-state">
                <i class="bi bi-bag-x"></i>
                <h2>Keranjang masih kosong</h2>
                <p>Pilih video edukasi dari Store untuk memulai.</p>
                <a class="btn store-primary" href="{{ route('store.index') }}">Buka Store</a>
            </div>
        @endforelse

        @if ($items->isNotEmpty())
            <div class="store-cart-total cart-checkout-total">
                <span>Total pesanan</span>
                <strong>Rp {{ number_format($items->sum(fn ($item) => $item->product->price * $item->quantity), 0, ',', '.') }}</strong>
            </div>
            <div class="text-end mt-4">
                <a href="{{ route('orders.checkout') }}" class="btn store-primary cart-checkout-btn">
                    <i class="bi bi-credit-card me-2"></i>Lakukan Pembayaran
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
