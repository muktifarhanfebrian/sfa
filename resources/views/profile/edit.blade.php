@extends('layouts.app')

@section('title', 'Edit Profil Saya')

@section('content')
<div class="row">

    <div class="col-md-6 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-person-circle"></i> Data Diri</h5>
            </div>
            <div class="card-body">

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="text-center mb-4">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" class="rounded-circle border border-3 border-primary shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto border" style="width: 120px; height: 120px;">
                                <i class="bi bi-person fs-1 text-secondary"></i>
                            </div>
                        @endif
                        <div class="mt-2">
                            <label class="btn btn-sm btn-outline-primary" for="photoInput">
                                <i class="bi bi-camera"></i> Ganti Foto
                            </label>
                            <input type="file" name="photo" id="photoInput" class="d-none" onchange="form.submit()"> </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. HP / WhatsApp</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow border-0 h-100 border-start border-warning border-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-key-fill text-warning"></i> Ganti Password</h5>
            </div>
            <div class="card-body">

                <div class="alert alert-light border small text-muted">
                    <i class="bi bi-info-circle"></i> Pastikan password baru minimal 6 karakter demi keamanan akun Anda.
                </div>

                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" required>
                        @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" required>
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-warning fw-bold text-dark">Update Password</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
