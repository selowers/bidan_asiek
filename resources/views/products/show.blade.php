@extends('layouts.app')
@section('title', $product->title)
@section('content')
<div class="store-page store-narrow"><div class="store-heading"><a class="store-back-link" href="{{ route('products.index') }}"><i class="bi bi-arrow-left me-1"></i>Kembali ke Produk</a><span class="store-kicker d-block mt-3"><i class="bi bi-collection-play"></i> Detail produk</span><h1>{{ $product->title }}</h1><p>{{ $product->description }}</p></div><div class="card store-form"><dl class="row mb-0"><dt class="col-sm-4">Harga</dt><dd class="col-sm-8">Rp {{ number_format($product->price, 0, ',', '.') }}</dd><dt class="col-sm-4">Masa akses</dt><dd class="col-sm-8">{{ $product->access_duration }} hari</dd><dt class="col-sm-4">Status</dt><dd class="col-sm-8">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</dd></dl></div></div>
@endsection
