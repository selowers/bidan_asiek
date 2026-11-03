<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Bidan Asiek')</title>
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
</head>
<body>
<div class="sidebar-wrapper" id="sidebar">
    <a href="{{ url('/') }}" class="sidebar-brand">
        <i class="bi bi-asterisk"></i>
        <span>Bidan Asiek</span>
    </a>
    <div class="flex-grow-1 overflow-y-auto">
        @if (auth()->user()?->isAdmin())
        <div class="sidebar-menu-section">
            <div class="sidebar-menu-title">Menu</div>
            <ul class="sidebar-menu-list">
                <li class="sidebar-menu-item">
                    <a href="{{ url('/') }}" class="sidebar-menu-link" title="Dashboard">
                        <i class="bi bi-grid-fill"></i><span>Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>
        @else
        <div class="sidebar-menu-section">
            <div class="sidebar-menu-title">Akun</div>
            <ul class="sidebar-menu-list"><li class="sidebar-menu-item"><a href="{{ route('profile') }}" class="sidebar-menu-link"><i class="bi bi-person-heart"></i><span>Profil Saya</span></a></li></ul>
        </div>
        @endif
        <div class="sidebar-menu-section">
            <div class="sidebar-menu-title">Klinik</div>
            <ul class="sidebar-menu-list">
                @if (auth()->user()?->isAdmin())
                <li class="sidebar-menu-item"><a href="{{ route('admin.clinic-services.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.clinic-services.*') ? 'active' : '' }}"><i class="bi bi-images"></i><span>Kelola Katalog</span></a></li>
                <li class="sidebar-menu-item">
                    <button class="sidebar-menu-link w-100 border-0 bg-transparent text-start {{ request()->routeIs('patients.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#patient-menu" aria-expanded="{{ request()->routeIs('patients.*') ? 'true' : 'false' }}" aria-controls="patient-menu" title="Data Pasien">
                        <i class="bi bi-people-fill"></i><span>Data Pasien</span><i class="bi bi-chevron-down dropdown-caret"></i>
                    </button>
                    <ul class="sidebar-submenu collapse {{ request()->routeIs('patients.*') ? 'show' : '' }}" id="patient-menu">
                        <li><a class="sidebar-submenu-link sidebar-submenu-link-general {{ request()->routeIs('patients.index') && request('category') === 'general' ? 'active' : '' }}" href="{{ route('patients.index', ['category' => 'general']) }}"><i class="bi bi-person-vcard-fill" aria-hidden="true"></i><span>Pasien Umum</span></a></li>
                        <li><a class="sidebar-submenu-link sidebar-submenu-link-kia {{ request()->routeIs('patients.index') && request('category') === 'kia' ? 'active' : '' }}" href="{{ route('patients.index', ['category' => 'kia']) }}"><i class="bi bi-heart-pulse-fill" aria-hidden="true"></i><span>Pasien KIA</span></a></li>
                    </ul>
                </li>
                @else
                <li class="sidebar-menu-item"><a href="{{ route('catalog.index') }}" class="sidebar-menu-link {{ request()->routeIs('catalog.*') ? 'active' : '' }}"><i class="bi bi-images"></i><span>Katalog Layanan</span></a></li>
                @endif
                <li class="sidebar-menu-item">
                    <a href="{{ route('appointments.index') }}" class="sidebar-menu-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}" title="{{ auth()->user()?->isAdmin() ? 'Kelola Janji Temu' : 'Janji Temu Saya' }}">
                        <i class="bi bi-calendar2-check"></i><span>{{ auth()->user()?->isAdmin() ? 'Kelola Janji Temu' : 'Janji Temu Saya' }}</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="sidebar-menu-section">
            <div class="sidebar-menu-title">E-Store</div>
            <ul class="sidebar-menu-list">
                @if (auth()->user()?->isAdmin())
                <li class="sidebar-menu-item"><a href="{{ route('admin.store') }}" class="sidebar-menu-link {{ request()->routeIs('admin.store') ? 'active' : '' }}"><i class="bi bi-shop-window"></i><span>E-Keranjang Store</span></a></li>
                <li class="sidebar-menu-item"><a href="{{ route('products.index') }}" class="sidebar-menu-link {{ request()->routeIs('products.*') ? 'active' : '' }}"><i class="bi bi-collection-play"></i><span>Kelola Produk</span></a></li>
                <li class="sidebar-menu-item"><a href="{{ route('admin.payment-methods') }}" class="sidebar-menu-link {{ request()->routeIs('admin.payment-methods') ? 'active' : '' }}"><i class="bi bi-wallet2"></i><span>Kelola Metode Pembayaran</span></a></li>
                <li class="sidebar-menu-item"><a href="{{ route('orders.index') }}" class="sidebar-menu-link {{ request()->routeIs('orders.*') ? 'active' : '' }}"><i class="bi bi-receipt"></i><span>Pesanan</span></a></li>
                <li class="sidebar-menu-item"><a href="{{ route('users.index') }}" class="sidebar-menu-link {{ request()->routeIs('users.*') ? 'active' : '' }}"><i class="bi bi-person-lock"></i><span>Kelola Pengguna</span></a></li>
                @else
                <li class="sidebar-menu-item"><a href="{{ route('store.index') }}" class="sidebar-menu-link {{ request()->routeIs('store.index') ? 'active' : '' }}"><i class="bi bi-shop"></i><span>Store Video</span></a></li>
                <li class="sidebar-menu-item"><a href="{{ route('cart.index') }}" class="sidebar-menu-link {{ request()->routeIs('cart.*') ? 'active' : '' }}"><i class="bi bi-bag-heart"></i><span>Keranjang</span></a></li>
                <li class="sidebar-menu-item"><a href="{{ route('orders.index') }}" class="sidebar-menu-link {{ request()->routeIs('orders.*') ? 'active' : '' }}"><i class="bi bi-receipt"></i><span>Pesanan Saya</span></a></li>
                @endif
            </ul>
        </div>
    </div>
    <div class="sidebar-profile">
        <img src="{{ asset('assets/images/avatar.png') }}" alt="Administrator" class="sidebar-profile-img">
        <div class="sidebar-profile-info">
            <div class="sidebar-profile-name">{{ auth()->user()->name }}</div>
            <div class="sidebar-profile-email">{{ ucfirst(auth()->user()->role) }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="sidebar-logout-form">@csrf<button class="sidebar-logout-btn" type="submit" title="Keluar"><i class="bi bi-box-arrow-right"></i><span>Keluar</span></button></form>
    </div>
</div>

<div class="main-wrapper">
    <header class="navbar-custom">
        <div class="navbar-left">
            <button class="btn-desktop-toggle d-none d-xl-flex align-items-center justify-content-center me-3" id="desktop-sidebar-toggle" aria-label="Minimize Sidebar">
                <i class="bi bi-chevron-bar-left"></i>
            </button>
            <button class="sidebar-toggle-btn me-2" id="sidebar-toggle" aria-label="Toggle Navigation">
                <i class="bi bi-list"></i>
            </button>
            <a class="fw-bold text-decoration-none" href="{{ url('/') }}">Bidan Asiek</a>
        </div>
    </header>
    <div class="container-fluid py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        @yield('content')
    </div>
</div>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
</body>
</html>
