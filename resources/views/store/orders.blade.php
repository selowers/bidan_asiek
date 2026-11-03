@extends('layouts.app')
@section('title', auth()->user()->isAdmin() ? 'Pesanan' : 'Pesanan Saya')
@section('content')
<div class="store-page">
    <div class="store-heading">
        <span class="store-kicker"><i class="bi bi-receipt"></i> E-Store</span>
        <h1>{{ auth()->user()->isAdmin() ? 'Pesanan Pasien' : 'Pesanan Saya' }}</h1>
        <p>{{ auth()->user()->isAdmin() ? 'Pantau dan proses pembayaran pasien dengan pengalaman yang lebih rapi dan modern.' : 'Pantau status pembelian video Anda dengan jelas.' }}</p>
    </div>

    @if (auth()->user()->isAdmin())
        <div class="store-order-summary">
            <div class="store-order-summary-card card-sunset">
                <span class="summary-icon"><i class="bi bi-receipt-cutoff"></i></span>
                <div>
                    <small>Total pesanan</small>
                    <strong>{{ $orders->total() }}</strong>
                </div>
            </div>
            <div class="store-order-summary-card card-mint">
                <span class="summary-icon"><i class="bi bi-hourglass-split"></i></span>
                <div>
                    <small>Menunggu</small>
                    <strong>{{ $orders->where('status', 'pending')->count() }}</strong>
                </div>
            </div>
            <div class="store-order-summary-card card-blue">
                <span class="summary-icon"><i class="bi bi-check2-circle"></i></span>
                <div>
                    <small>Selesai</small>
                    <strong>{{ $orders->where('status', 'paid')->count() }}</strong>
                </div>
            </div>
            <div class="store-order-summary-card card-rose">
                <span class="summary-icon"><i class="bi bi-x-circle"></i></span>
                <div>
                    <small>Ditolak</small>
                    <strong>{{ $orders->where('status', 'rejected')->count() }}</strong>
                </div>
            </div>
        </div>
    @else
        <div class="store-order-patient-summary">
            <div class="store-order-summary-card card-sunset">
                <span class="summary-icon"><i class="bi bi-bag-check"></i></span>
                <div>
                    <small>Total pesanan</small>
                    <strong>{{ $orders->total() }}</strong>
                </div>
            </div>
            <div class="store-order-summary-card card-mint">
                <span class="summary-icon"><i class="bi bi-hourglass-split"></i></span>
                <div>
                    <small>Menunggu</small>
                    <strong>{{ $orders->where('status', 'pending')->count() }}</strong>
                </div>
            </div>
            <div class="store-order-summary-card card-blue">
                <span class="summary-icon"><i class="bi bi-check2-circle"></i></span>
                <div>
                    <small>Berhasil</small>
                    <strong>{{ $orders->where('status', 'paid')->count() }}</strong>
                </div>
            </div>
            <div class="store-order-summary-card card-rose">
                <span class="summary-icon"><i class="bi bi-play-circle"></i></span>
                <div>
                    <small>Video aktif</small>
                    <strong>{{ $orders->where('status', 'paid')->sum(fn ($order) => $order->items->count()) }}</strong>
                </div>
            </div>
        </div>
    @endif

    <div class="card store-order-card store-order-panel {{ auth()->user()->isAdmin() ? '' : 'store-order-patient-panel' }}">
        <div class="store-order-toolbar">
            <div>
                <h2>{{ auth()->user()->isAdmin() ? 'Daftar transaksi pasien' : 'Riwayat pembelian' }}</h2>
                <p>{{ auth()->user()->isAdmin() ? 'Konfirmasi, tinjau bukti pembayaran, dan kelola status order.' : 'Semua pembelanjaan Anda tersaji di sini dengan status yang jelas.' }}</p>
            </div>
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.store') }}" class="btn btn-sm store-order-link"><i class="bi bi-arrow-left me-2"></i>Kembali ke dashboard</a>
            @else
                <a href="{{ route('store.index') }}" class="btn btn-sm store-order-patient-link"><i class="bi bi-bag-plus me-2"></i>Lanjut beli</a>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0 store-order-table">
                <thead>
                    <tr>
                        <th>Pesanan</th>
                        @if (auth()->user()->isAdmin())
                            <th>Pasien</th>
                        @endif
                        <th>Total</th>
                        @if (auth()->user()->isAdmin())
                            <th>Bukti bayar</th>
                        @endif
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>
                                <div class="store-order-identity">
                                    <div>
                                        <strong>{{ $order->order_number }}</strong>
                                        <small>{{ $order->created_at->format('d M Y, H:i') }}</small>
                                    </div>
                                </div>
                            </td>
                            @if (auth()->user()->isAdmin())
                                <td>
                                    <div class="store-order-user">
                                        <span>{{ $order->user->name }}</span>
                                    </div>
                                </td>
                            @endif
                            <td>
                                <div class="store-order-total">
                                    <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                                    <small>{{ $order->items->count() }} video</small>
                                </div>
                            </td>
                            @if (auth()->user()->isAdmin())
                                <td>
                                    @if ($order->payment_proof_path)
                                        <a href="{{ asset('storage/'.$order->payment_proof_path) }}" target="_blank" rel="noopener" class="store-proof-link">
                                            <img src="{{ asset('storage/'.$order->payment_proof_path) }}" alt="Bukti pembayaran {{ $order->order_number }}" />
                                        </a>
                                    @else
                                        <span class="store-proof-empty">Belum ada</span>
                                    @endif
                                </td>
                            @endif
                            <td>
                                <span class="store-order-status store-order-status-{{ $order->status }}">{{ $order->status_label }}</span>
                            </td>
                            <td class="text-end">
                                @if (auth()->user()->isAdmin() && $order->status === 'pending')
                                    <div class="store-order-actions">
                                        <form method="POST" action="{{ route('orders.paid', $order) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm store-order-confirm"><i class="bi bi-check2-circle me-1"></i>Konfirmasi</button>
                                        </form>
                                        <form method="POST" action="{{ route('orders.reject', $order) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm store-order-reject"><i class="bi bi-x-circle me-1"></i>Tolak</button>
                                        </form>
                                    </div>
                                @elseif ($order->status === 'paid')
                                    <a href="{{ route('store.index') }}" class="btn btn-sm store-order-view"><i class="bi bi-play-circle me-1"></i>Lihat video</a>
                                @elseif ($order->status === 'rejected')
                                    <span class="store-order-muted">Pembayaran ditolak</span>
                                @else
                                    <span class="store-order-muted">Belum ada aksi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin() ? 6 : 4 }}" class="text-center py-5">
                                <div class="store-order-empty">
                                    <i class="bi bi-bag-check"></i>
                                    <h4>{{ auth()->user()->isAdmin() ? 'Belum ada pesanan' : 'Belum ada pembelian' }}</h4>
                                    <p>{{ auth()->user()->isAdmin() ? 'Semua transaksi akan muncul di sini setelah pasien membuat order.' : 'Belum ada video yang Anda beli. Jelajahi store untuk mulai menonton.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="store-order-pagination">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
