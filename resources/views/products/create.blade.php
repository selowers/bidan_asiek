@extends('layouts.app')
@section('title', 'Tambah Produk Video')
@section('content')
<div class="store-page store-narrow"><div class="store-heading"><span class="store-kicker"><i class="bi bi-stars"></i> Produk baru</span><h1>Tambah Produk Video</h1><p>Publikasikan materi digital dengan akses yang terukur.</p></div><form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="store-form card">@csrf @include('products.form')<div class="d-flex justify-content-end gap-2"><a class="btn btn-light" href="{{ route('products.index') }}">Batal</a><button class="btn store-primary"><i class="bi bi-check2 me-2"></i>Simpan Produk</button></div></form></div>
@endsection
