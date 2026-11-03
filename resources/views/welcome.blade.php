<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bidan Asiek - Dashboard Admin</title>

  <!-- SEO Optimization -->
  <meta name="description" content="Dashboard administrasi Bidan Asiek">
  <meta name="author" content="Bidan Asiek">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="assets/images/favicon.ico">

  <!-- Local Third-Party Libraries (100% Offline Compatible) -->
  <link rel="stylesheet" href="assets/libs/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/libs/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/libs/apexcharts/apexcharts.css">
  <link rel="stylesheet" href="assets/libs/flatpickr/flatpickr.min.css">

  <!-- Main Design System & Custom Stylesheet -->
  <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>

  <!-- ==========================================
         START: Sidebar Component
         Highly polished, dark-green sticky navigation
         ========================================== -->
  <div class="sidebar-wrapper" id="sidebar">
    <!-- Brand Logo / Identity -->
    <a href="index.html" class="sidebar-brand">
      <i class="bi bi-asterisk"></i>
      <span>Bidan Asiek</span>
    </a>

    <!-- Navigation Menu -->
    <div class="flex-grow-1 overflow-y-auto">
      <!-- Group: Menu -->
      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">Menu</div>
        <ul class="sidebar-menu-list">
          <li class="sidebar-menu-item">
            <a href="index.html" class="sidebar-menu-link active" id="menu-overview" title="Overview">
              <i class="bi bi-grid-fill"></i>
              <span>Dashboard</span>
            </a>
          </li>
        </ul>
      </div>

      <!-- Group: Klinik -->
      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">Klinik</div>
        <ul class="sidebar-menu-list">
          <li class="sidebar-menu-item">
            <a href="{{ route('patients.index') }}" class="sidebar-menu-link" id="menu-patients" title="Data Pasien">
              <i class="bi bi-people-fill"></i>
              <span>Data Pasien</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('appointments.index') }}" class="sidebar-menu-link" id="menu-appointments" title="Janji Temu">
              <i class="bi bi-calendar2-check"></i>
              <span>Janji Temu</span>
            </a>
          </li>
        </ul>
      </div>

      <!-- Group: E-Store -->
      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">E-Store</div>
        <ul class="sidebar-menu-list">
          <li class="sidebar-menu-item">
            <a href="{{ route('admin.store') }}" class="sidebar-menu-link" title="E-Keranjang Store">
              <i class="bi bi-shop-window"></i>
              <span>E-Keranjang Store</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('products.index') }}" class="sidebar-menu-link" title="Kelola Produk">
              <i class="bi bi-collection-play"></i>
              <span>Kelola Produk</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('orders.index') }}" class="sidebar-menu-link" title="Pesanan">
              <i class="bi bi-receipt"></i>
              <span>Pesanan</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('users.index') }}" class="sidebar-menu-link" title="Kelola Pengguna">
              <i class="bi bi-person-lock"></i>
              <span>Kelola Pengguna</span>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Sidebar Profile Card (Dynamic Footer) -->
    <div class="sidebar-profile">
      <img src="assets/images/avatar.png" alt="Administrator" class="sidebar-profile-img"
        onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop'">
      <div class="sidebar-profile-info">
        <div class="sidebar-profile-name">{{ auth()->user()->name }}</div>
        <div class="sidebar-profile-email">{{ ucfirst(auth()->user()->role) }}</div>
      </div>
    </div>
  </div>
  <!-- ==========================================
         END: Sidebar Component
         ========================================== -->


  <!-- ==========================================
         START: Main Content Area
         ========================================== -->
  <div class="main-wrapper">

    <!-- START: Top Navbar Component -->
    <header class="navbar-custom">
      <div class="navbar-left">
        <!-- Desktop sidebar toggle (visible on large screens only) -->
        <button class="btn-desktop-toggle d-none d-xl-flex align-items-center justify-content-center me-3"
          id="desktop-sidebar-toggle" aria-label="Minimize Sidebar">
          <i class="bi bi-chevron-bar-left"></i>
        </button>
        <!-- Mobile sidebar toggle -->
        <button class="sidebar-toggle-btn me-2" id="sidebar-toggle" aria-label="Toggle Navigation">
          <i class="bi bi-list"></i>
        </button>
      </div>

      <!-- Right actions -->
      <div class="navbar-actions">
        <!-- Fullscreen Toggle -->
        <button class="navbar-action-btn me-1" aria-label="Toggle Fullscreen" id="btn-fullscreen">
          <i class="bi bi-arrows-fullscreen"></i>
        </button>
        <div class="dropdown">
          <button class="navbar-action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false" id="btn-notifications" data-bs-auto-close="outside">
            <i class="bi bi-bell"></i>
            <span class="navbar-action-badge"></span>
          </button>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-notification p-0"
            aria-labelledby="btn-notifications">
            <div class="notification-header">
              <h6 class="notification-title">Notifications</h6>
              <button class="btn-clear-all" type="button">Mark all read</button>
            </div>
            <div class="notification-list">
              <!-- Sale Notification -->
              <a href="#" class="notification-item">
                <div class="notification-icon bg-success text-white">
                  <i class="bi bi-wallet2"></i>
                </div>
                <div class="notification-content">
                  <p class="notification-text">New sale received: <strong>$150.00</strong></p>
                  <span class="notification-time">2 mins ago</span>
                </div>
                <span class="notification-unread-dot"></span>
              </a>
              <!-- User Registration Notification -->
              <a href="#" class="notification-item">
                <div class="notification-icon bg-primary text-white">
                  <i class="bi bi-person-plus-fill"></i>
                </div>
                <div class="notification-content">
                  <p class="notification-text">New user registered: <strong>John Doe</strong></p>
                  <span class="notification-time">1 hour ago</span>
                </div>
                <span class="notification-unread-dot"></span>
              </a>
              <!-- Low Stock Notification -->
              <a href="#" class="notification-item">
                <div class="notification-icon bg-warning text-dark">
                  <i class="bi bi-box-seam-fill"></i>
                </div>
                <div class="notification-content">
                  <p class="notification-text">Stock running low: <strong>Hoodie</strong></p>
                  <span class="notification-time">3 hours ago</span>
                </div>
              </a>
            </div>
            <a href="#" class="notification-footer">View All Notifications</a>
          </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="dropdown ms-2">
          <button class="navbar-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false" id="profile-dropdown">
            <img src="assets/images/avatar.png" alt="Profile Image" class="navbar-profile-img">
            <span class="navbar-profile-name d-none d-md-inline">Administrator</span>
            <i class="bi bi-chevron-down navbar-profile-caret"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-profile" aria-labelledby="profile-dropdown">
            <li class="dropdown-header">Welcome !</li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> My Account</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Settings</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-lock"></i> Lock Screen</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><form method="POST" action="{{ route('logout') }}">@csrf<button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right"></i> Logout</button></form></li>
          </ul>
        </div>
      </div>
    </header>
    <!-- END: Top Navbar Component -->

    <!-- START: Dashboard Header Banner -->
    <div class="page-header">
      <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Pemasukan video, kunjungan pasien, dan janji temu dalam satu dashboard.</p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <span class="text-muted-green small">Filter tersedia di grafik</span>
      </div>
    </div>
    <!-- END: Dashboard Header Banner -->

    <div class="dashboard-highlight-row row g-4 mb-4">
      <div class="col-lg-4 col-md-6">
        <div class="mini-summary-card mini-summary-card--dark">
          <div class="mini-summary-top">
            <span class="mini-summary-tag">Update</span>
            <span class="mini-summary-date">{{ now()->translatedFormat('d M Y') }}</span>
          </div>
          <div class="mini-summary-content">
            <div class="mini-summary-label">Pemasukan bulan ini</div>
            <div class="mini-summary-value">Rp {{ number_format($revenueTotal, 0, ',', '.') }}</div>
          </div>
          <div class="mini-summary-cta">
            <span>Lihat statistik</span>
            <i class="bi bi-arrow-right"></i>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="mini-summary-card mini-summary-card--light">
          <div class="mini-summary-header">
            <span class="mini-summary-label muted">Pemasukan video</span>
            <span class="mini-summary-pill success">Aktif</span>
          </div>
          <div class="mini-summary-value large">Rp {{ number_format($revenueTotal, 0, ',', '.') }}</div>
          <div class="mini-summary-footer">
            <span class="mini-summary-dot green"></span>
            <span>Berjalan lancar</span>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-12">
        <div class="mini-summary-card mini-summary-card--light soft-accent">
          <div class="mini-summary-header">
            <span class="mini-summary-label muted">Pasien terdaftar</span>
            <span class="mini-summary-pill neutral">{{ number_format($patientCount) }}</span>
          </div>
          <div class="mini-summary-value large">{{ number_format($patientCount) }}</div>
          <div class="mini-summary-footer">
            <span class="mini-summary-dot orange"></span>
            <span>{{ $period === 'year' ? 'Tahun ini' : 'Bulan ini' }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- START: Main Layout Grid (2 Columns: Dashboard + Performance Pane) -->
    <div class="row g-4">

      <!-- LEFT AREA: Primary Dashboard Stats & Tables -->
      <div class="col-12">

        <!-- START: Details Area (Transactions + Performance Charts) -->
        <div class="row g-4">
          <!-- Column: Revenue Chart (Full Width / Wider) -->
          <div class="col-12">
            <div class="card mb-0">
              <div class="card-header mb-2">
                <h2 class="card-title" id="chart-title">{{ $metric === 'income' ? 'Pemasukan' : ($metric === 'patients' ? 'Data Pasien' : 'Data Janji Temu') }}</h2>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                  <form method="GET" action="{{ route('home') }}" class="d-flex gap-2 align-items-center" id="dashboard-chart-filter">
                    <select name="period" class="form-select form-select-sm" aria-label="Pilih periode">
                      <option value="month" @selected($period === 'month')>Bulanan</option>
                      <option value="year" @selected($period === 'year')>Tahunan</option>
                    </select>
                    <select name="metric" class="form-select form-select-sm" aria-label="Pilih data grafik">
                      <option value="income" @selected($metric === 'income')>Pemasukan</option>
                      <option value="patients" @selected($metric === 'patients')>Data pasien</option>
                      <option value="appointments" @selected($metric === 'appointments')>Data janji temu</option>
                    </select>
                    <select name="year" class="form-select form-select-sm" aria-label="Pilih tahun" style="{{ $period === 'year' ? '' : 'display:none;' }}">
                      @foreach ($yearOptions as $yearOption)
                        <option value="{{ $yearOption }}" @selected((int) $selectedYear === (int) $yearOption)>
                          {{ $yearOption }}
                        </option>
                      @endforeach
                    </select>

                    <select name="month" class="form-select form-select-sm" aria-label="Pilih bulan" style="{{ $period === 'year' ? 'display:none;' : '' }}">
                      @for ($monthIndex = 1; $monthIndex <= 12; $monthIndex++)
                        @php($monthValue = now()->year.'-'.str_pad($monthIndex, 2, '0', STR_PAD_LEFT))
                        <option value="{{ $monthValue }}" @selected($selectedMonth === $monthValue)>
                          {{ \Carbon\Carbon::create(now()->year, $monthIndex, 1)->translatedFormat('F Y') }}
                        </option>
                      @endfor
                    </select>
                    <button type="submit" class="btn btn-success btn-sm">Tampilkan</button>
                  </form>
                  <a href="{{ route('dashboard.export', ['period' => $period, 'month' => $selectedMonth, 'metric' => $metric]) }}" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-file-earmark-excel"></i>
                  </a>
                </div>
              </div>
              <div class="d-flex align-items-baseline gap-2 mb-3">
                <span class="stat-value-amount" id="chart-total">
                  @if ($metric === 'income')
                    Rp {{ number_format($metricTotal, 0, ',', '.') }}
                  @else
                    {{ number_format($metricTotal) }} data
                  @endif
                </span>
                <span class="trend-badge trend-up fs-xs">{{ $period === 'year' ? 'Periode tahunan' : \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->translatedFormat('F Y') }}</span>
              </div>
              <div id="revenue-chart" data-chart='@json($chartData)'></div>
            </div>
          </div>

          <!-- Column: Confirmed Paid Patients -->
          <div class="col-md-7 d-flex flex-column">
            <div class="card h-100 flex-grow-1">
              <div class="card-header">
                <h2 class="card-title">Pasien Terkonfirmasi</h2>
                <div class="dropdown">
                  <button class="card-more-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                    aria-label="More Options" id="btn-more-transaction">
                    <i class="bi bi-three-dots"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-funnel"></i> Filter Status</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-arrow-down"></i> Export CSV</a>
                    </li>
                  </ul>
                </div>
              </div>

              <div class="transaction-list">
                @forelse ($paidPatients as $patient)
                  <div class="transaction-item">
                    <div class="transaction-icon bg-forest-light text-lime">
                      <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="transaction-info">
                      <div class="transaction-name">{{ $patient['name'] }}</div>
                      <div class="transaction-date">{{ $patient['paid_at'] }}</div>
                    </div>
                    <div class="transaction-amount text-success">+Rp {{ number_format($patient['amount'], 0, ',', '.') }}</div>
                  </div>
                @empty
                  <div class="transaction-item">
                    <div class="transaction-info">
                      <div class="transaction-name">Belum ada pasien dengan pembayaran terkonfirmasi.</div>
                    </div>
                  </div>
                @endforelse
              </div>
            </div>
          </div>

          <!-- Column: Top Selling Videos -->
          <div class="col-md-5 d-flex flex-column">
            <div class="card h-100 flex-grow-1">
              <div class="card-header">
                <h2 class="card-title">Produk Terlaris</h2>
                <div class="dropdown">
                  <button class="card-more-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                    aria-label="More Options" id="btn-more-products">
                    <i class="bi bi-three-dots"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-plus-lg"></i> Add Product</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Manage</a></li>
                  </ul>
                </div>
              </div>

              @foreach ($topProductList as $product)
                <div class="progress-container">
                  <div class="progress-label-row">
                    <span class="progress-label">{{ $product['name'] }}</span>
                    <span class="progress-value">{{ $product['quantity'] }}</span>
                  </div>
                  <div class="progress" role="progressbar" aria-label="{{ $product['name'] }} progress" aria-valuenow="{{ $product['width'] }}" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar bg-lime-accent" style="width: {{ $product['width'] }}%"></div>
                  </div>
                </div>
              @endforeach
            </div>

          </div>
        </div>
        <!-- END: Details Area -->

      </div>

    </div>
    <!-- END: Main Layout Grid -->

    <!-- START: Footer Component -->
    <footer class="footer-custom">
      <div class="footer-left">
        <span class="footer-logo">
          <i class="bi bi-asterisk"></i> Bidan Asiek
        </span>
        <span class="footer-separator">|</span>
        <span class="footer-copy">
          <i class="bi bi-geo-alt-fill"></i>
          Jalan Sawunggaling, RT.1/RW.1, Sumbersari, Dusun Sipring, Kec. Pagelaran, Kabupaten Malang, Jawa Timur 65174
        </span>
      </div>
      <div class="footer-right">
        <ul class="footer-links">
          <li><span class="footer-link">Layanan Kesehatan Ibu dan Anak</span></li>
          <li><span class="footer-link">Status <span class="status-dot"></span></span></li>
        </ul>
      </div>
    </footer>
    <!-- END: Footer Component -->

  </div>
  <!-- ==========================================
         END: Main Content Area
         ========================================== -->

  <!-- Local Third-Party Libraries Script dependencies -->
  <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/libs/apexcharts/apexcharts.min.js"></script>
  <script src="assets/libs/flatpickr/flatpickr.min.js"></script>

  <!-- Local dashboard interactions controller -->
  <script src="assets/js/dashboard.js"></script>
</body>

</html>