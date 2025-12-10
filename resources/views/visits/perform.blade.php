@extends('layouts.app')

@section('title', 'Eksekusi Kunjungan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow border-0">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-geo-alt-fill"></i> Check-in: {{ $visit->customer->name }}</h5>
            </div>
            <div class="card-body">

                <div class="alert alert-light border mb-3">
                    <small class="text-muted d-block">Catatan Rencana:</small>
                    <strong>"{{ $visit->notes ?? '-' }}"</strong>
                </div>

                <form action="{{ route('visits.update', $visit->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <div class="mb-3">
                        <label class="form-label fw-bold">Lokasi Anda Sekarang</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-light"><i class="bi bi-geo"></i></span>
                            <input type="text" id="locationDisplay" class="form-control" readonly placeholder="Wajib ambil lokasi">
                        </div>
                        <input type="hidden" name="latitude" id="lat">
                        <input type="hidden" name="longitude" id="long">
                        <button type="button" onclick="getLocation()" class="btn btn-outline-success w-100" id="btnGetLoc">
                            <i class="bi bi-crosshair"></i> Ambil Lokasi
                        </button>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Bukti Foto Realisasi</label>
                        <input type="file" name="photo" class="form-control" accept="image/*" capture="environment" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Update Laporan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="2">{{ $visit->notes }}</textarea>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-success btn-lg fw-bold">Selesaikan Kunjungan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function getLocation() {
        const btn = document.getElementById('btnGetLoc');
        const display = document.getElementById('locationDisplay');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    document.getElementById('lat').value = pos.coords.latitude;
                    document.getElementById('long').value = pos.coords.longitude;
                    display.value = pos.coords.latitude + ", " + pos.coords.longitude;
                    btn.className = "btn btn-success w-100";
                    btn.innerHTML = '<i class="bi bi-check-circle"></i> Lokasi OK';
                },
                (err) => {
                    alert("Gagal ambil lokasi. Pastikan GPS aktif.");
                    btn.innerHTML = 'Coba Lagi';
                },
                { enableHighAccuracy: true }
            );
        } else { alert("Browser tidak support GPS."); }
    }
</script>
@endsection
