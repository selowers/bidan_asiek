@extends('layouts.app')
@section('title', 'Edit Produk Video')
@section('content')
<div class="store-page store-narrow"><div class="store-heading"><span class="store-kicker"><i class="bi bi-pencil-square"></i> Perbarui produk</span><h1>Edit Produk Video</h1><p>Sesuaikan harga, konten, dan masa aktif akses.</p></div><form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data" class="store-form card">@csrf @method('PUT') @include('products.form')<div class="d-flex justify-content-end gap-2"><a class="btn btn-light" href="{{ route('products.index') }}">Batal</a><button class="btn store-primary"><i class="bi bi-check2 me-2"></i>Simpan Perubahan</button></div></form></div>
@endsection
