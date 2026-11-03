<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Masuk') · Bidan Asiek</title>
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
</head>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-intro">
            <a class="auth-brand" href="{{ url('/') }}"><i class="bi bi-asterisk"></i>Bidan Asiek</a>
            <div class="auth-intro-content"><span class="auth-intro-icon"><i class="bi bi-heart-pulse"></i></span><h1>Perawatan yang lebih terhubung.</h1><p>Kelola jadwal kontrol dan informasi kesehatan dalam satu ruang yang aman.</p></div>
            <div class="auth-intro-footer"><i class="bi bi-shield-check"></i>Privasi pasien selalu menjadi prioritas.</div>
        </section>
        <section class="auth-panel">
            <div class="auth-panel-inner">
                @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
                @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                @yield('content')
            </div>
        </section>
    </main>
</body>
</html>
