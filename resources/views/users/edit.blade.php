@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Edit User: {{ $user->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name', $user->name) }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No HP / WhatsApp</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="sales" {{ $user->role == 'sales' ? 'selected' : '' }}>Sales Lapangan</option>
                                <option value="manager" {{ $user->role == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Target Visit Harian</label>
                            <input type="number" name="daily_visit_target" class="form-control" value="{{ old('daily_visit_target', $user->daily_visit_target) }}" min="0">
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <label class="form-label fw-bold">Ubah Password (Opsional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengganti password">
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
