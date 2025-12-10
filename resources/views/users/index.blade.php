@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-gray-800">Manajemen User & Sales</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill"></i> Tambah User Baru
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama & Email</th>
                            <th>Role / Jabatan</th>
                            <th class="text-center">Target Visit (Harian)</th>
                            <th>Terdaftar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                    @if ($user->phone)
                                        <br><small class="text-success"><i class="bi bi-whatsapp"></i>
                                            {{ $user->phone }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if ($user->role == 'admin')
                                        <span class="badge bg-dark">ADMIN</span>
                                    @elseif($user->role == 'manager')
                                        <span class="badge bg-primary">MANAGER</span>
                                    @else
                                        <span class="badge bg-info text-dark">SALES</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($user->role == 'sales')
                                        <span class="fw-bold fs-5 text-primary">{{ $user->daily_visit_target }}</span>
                                        <small class="text-muted d-block">Toko / Hari</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    @if (auth()->id() != $user->id)
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Yakin hapus user ini? Data order & visitnya juga akan terhapus!');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">Belum ada data user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
