@extends('layouts.app')
@section('title', 'Kelola Pengguna')
@section('content')
<div class="store-page user-management-page">
    <div class="store-hero"><div><span class="store-kicker"><i class="bi bi-person-lock"></i> Akses sistem</span><h1>Kelola Pengguna</h1><p>Daftarkan akun pasien dan perawat sesuai kebutuhan klinik.</p></div><a class="btn store-primary" href="{{ route('users.create') }}"><i class="bi bi-person-plus me-2"></i>Tambah Akun</a></div>
    <div class="user-management-card card"><div class="user-management-header"><div><h2 class="h5 mb-1">Daftar akun</h2><p class="text-muted small mb-0">Akun pasien terhubung ke profil pasien, akun perawat mendapat akses admin.</p></div><span class="user-count"><i class="bi bi-people me-1"></i>{{ $users->total() }} akun</span></div>
        <div class="table-responsive"><table class="table user-table align-middle mb-0"><thead><tr><th>Pengguna</th><th>Email</th><th>Jenis akun</th><th>Terhubung ke</th><th class="text-end">Aksi</th></tr></thead><tbody>
        @forelse ($users as $user)<tr><td><div class="user-identity"><span><strong>{{ $user->name }}</strong><small class="text-muted d-block">Dibuat {{ $user->created_at->format('d M Y') }}</small></span></div></td><td>{{ $user->email }}</td><td><span class="user-role user-role-{{ $user->isAdmin() ? 'nurse' : 'patient' }}"><i class="bi bi-{{ $user->isAdmin() ? 'person-badge' : 'person-heart' }}"></i>{{ $user->isAdmin() ? 'Perawat / Admin' : 'Pasien' }}</span></td><td>{{ $user->patient?->name ?: 'Akses staf klinik' }}</td><td class="text-end"><div class="d-flex justify-content-end gap-2 align-items-center">@if (! $user->is(auth()->user()))<a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></a><form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus akun ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>@else<span class="text-muted small">Akun Anda</span>@endif</div></td></tr>@empty<tr><td colspan="5" class="text-center py-5 text-muted">Belum ada akun pengguna.</td></tr>@endforelse
        </tbody></table></div>@if ($users->hasPages())<div class="p-4 border-top">{{ $users->links() }}</div>@endif
    </div>
</div>
@endsection
