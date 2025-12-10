@extends('layouts.app')

@section('title', 'Tambah Toko Baru')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Perbarui Toko</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nama Toko</label>
                            <input type="text" name="name" class="form-control" required
                                value="{{ old('name', $customer->name) }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Pemilik / PIC</label>
                                <input type="text" name="contact_person" class="form-control"
                                    value="{{ old('contact_person', $customer->contact_person) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon / WA</label>
                                <input type="text" name="phone" class="form-control" required
                                    value="{{ old('phone', $customer->phone) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="address" class="form-control" rows="3" required>{{ old('address', $customer->address) }}</textarea>
                        </div>

                        <hr>
                        <h6 class="fw-bold text-primary">Pengaturan Pembayaran (Admin Only)</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Term of Payment (Hari)</label>
                                <input type="number" name="top_days" class="form-control"
                                    value="{{ old('top_days', $customer->top_days) }}">
                                <div class="form-text">0 = Tunai / Cash.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Limit Kredit (Rp)</label>
                                <input type="number" name="credit_limit" class="form-control"
                                    value="{{ old('credit_limit', $customer->credit_limit) }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('customers.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Toko</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
