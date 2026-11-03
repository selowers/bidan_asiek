@extends('layouts.app')
@section('title', 'E-Keranjang Store')
@section('content')
<div class="store-page">
    <div class="store-hero">
        <div><span class="store-kicker"><i class="bi bi-shop-window"></i> Admin workspace</span><h1>E-Keranjang Store</h1><p>Kelola video berbayar, masa akses, dan pesanan pasien dari satu tempat.</p></div>
        <div class="d-flex gap-2 position-relative"><a class="btn store-cart-button" href="{{ route('store.index') }}"><i class="bi bi-eye me-2"></i>Lihat Store Pasien</a><a class="btn store-primary" href="{{ route('products.create') }}"><i class="bi bi-plus-lg me-2"></i>Tambah Produk</a></div>
    </div>

    <div class="store-admin-stats">
        <div class="store-admin-stat store-admin-stat-coral"><span><i class="bi bi-collection-play"></i></span><small>Total produk</small><strong>{{ $productCount }}</strong></div>
        <div class="store-admin-stat store-admin-stat-lime"><span><i class="bi bi-broadcast"></i></span><small>Produk aktif</small><strong>{{ $activeProductCount }}</strong></div>
        <div class="store-admin-stat store-admin-stat-amber"><span><i class="bi bi-hourglass-split"></i></span><small>Menunggu bayar</small><strong>{{ $pendingOrderCount }}</strong></div>
        <div class="store-admin-stat store-admin-stat-blue"><span><i class="bi bi-check2-circle"></i></span><small>Pesanan selesai</small><strong>{{ $paidOrderCount }}</strong></div>
    </div>

    <div class="card store-admin-orders mb-4 store-admin-mini-panel">
        <div class="store-order-toolbar store-order-toolbar-mini">
            <div>
                <h2>Pesanan terbaru</h2>
                <p>Konfirmasi pembayaran untuk membuka akses video.</p>
            </div>
            <a class="store-back-link" href="{{ route('orders.index') }}">Lihat semua <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="table-responsive"><table class="table align-middle mb-0 store-order-table"><thead><tr><th>Pesanan</th><th>Pasien</th><th>Bukti bayar</th><th>Status</th><th class="text-end">Aksi</th></tr></thead><tbody>@forelse ($recentOrders as $order)<tr><td><div class="store-order-identity"><div><strong>{{ $order->order_number }}</strong><small>{{ $order->created_at->format('d M Y, H:i') }}</small></div></div></td><td><div class="store-order-user"><span>{{ $order->user->name }}</span></div></td><td>@if ($order->payment_proof_path)<a href="{{ asset('storage/'.$order->payment_proof_path) }}" target="_blank" rel="noopener" class="store-proof-link"><img src="{{ asset('storage/'.$order->payment_proof_path) }}" alt="Bukti pembayaran {{ $order->order_number }}" /></a>@else<span class="store-proof-empty">Belum ada</span>@endif</td><td><span class="store-order-status store-order-status-{{ $order->status }}">{{ $order->status_label }}</span></td><td class="text-end">@if ($order->status === 'pending')<div class="store-order-actions"><form method="POST" action="{{ route('orders.paid', $order) }}">@csrf @method('PATCH')<button class="btn btn-sm store-order-confirm"><i class="bi bi-check2-circle me-1"></i>Konfirmasi</button></form><form method="POST" action="{{ route('orders.reject', $order) }}">@csrf @method('PATCH')<button class="btn btn-sm store-order-reject"><i class="bi bi-x-circle me-1"></i>Tolak</button></form></div>@else<span class="store-order-muted">{{ $order->status_label }}</span>@endif</td></tr>@empty<tr><td colspan="5" class="text-center py-4 text-muted">Belum ada pesanan.</td></tr>@endforelse</tbody></table></div>
    </div>
</div>
@endsection
