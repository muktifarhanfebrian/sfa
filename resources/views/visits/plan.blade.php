@extends('layouts.app')

@section('title', 'Buat Rencana Kunjungan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow border-0">
            <div class="card-header bg-info text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-plus"></i> Rencana Kunjungan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('visits.storePlan') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Toko</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Rencana</label>
                        <input type="date" name="visit_date" class="form-control" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan Rencana</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Mau nagih utang atau tawarkan produk baru"></textarea>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-info text-white fw-bold">Simpan Rencana</button>
                        <a href="{{ route('dashboard') }}" class="btn btn-link text-muted mt-2">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
