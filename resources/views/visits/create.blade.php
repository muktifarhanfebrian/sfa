@extends('layouts.app')

@section('title', 'Check-in Kunjungan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-geo-alt-fill"></i> Check-in Visit</h5>
            </div>
            <div class="card-body">

                <form action="{{ route('visits.store') }}" method="POST" enctype="multipart/form-data" id="visitForm">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Toko / Pelanggan</label>
                        <select name="customer_id" class="form-select form-select-lg" required>
                            <option value="">-- Cari Nama Toko --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Lokasi Anda</label>

                        <div class="input-group mb-2">
                            <span class="input-group-text bg-light"><i class="bi bi-geo"></i></span>
                            <input type="text" id="locationDisplay" class="form-control" placeholder="Koordinat belum diambil" readonly>
                        </div>

                        <input type="hidden" name="latitude" id="lat">
                        <input type="hidden" name="longitude" id="long">

                        <button type="button" onclick="getLocation()" class="btn btn-outline-primary w-100" id="btnGetLoc">
                            <i class="bi bi-crosshair"></i> Ambil Lokasi Saya
                        </button>
                        <small class="text-danger d-none" id="gpsError">Gagal mengambil lokasi. Pastikan GPS aktif.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Bukti Foto</label>
                        <input type="file" name="photo" class="form-control" accept="image/*" capture="environment" required>
                        <div class="form-text">Wajib foto tampak depan toko / selfie dengan pemilik.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Laporan Hasil</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Pemilik minta kirim sampel keramik baru..."></textarea>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">
                            <i class="bi bi-send-check"></i> Simpan Kunjungan
                        </button>
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
        const gpsError = document.getElementById('gpsError');

        // Ubah tombol jadi loading
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Sedang mencari satelit...';
        btn.disabled = true;

        if (navigator.geolocation) {
            // Tambahkan parameter ketiga: options
            navigator.geolocation.getCurrentPosition(showPosition, showError, {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            });
        } else {
            display.value = "Browser tidak support GPS.";
        }
    }

    function showPosition(position) {
        // Isi input hidden
        document.getElementById('lat').value = position.coords.latitude;
        document.getElementById('long').value = position.coords.longitude;

        // Tampilkan di layar agar user tau sukses
        document.getElementById('locationDisplay').value =
            position.coords.latitude + ", " + position.coords.longitude;

        // Kembalikan tombol jadi hijau
        const btn = document.getElementById('btnGetLoc');
        btn.className = "btn btn-success w-100";
        btn.innerHTML = '<i class="bi bi-check-circle"></i> Lokasi Terkunci';
        document.getElementById('gpsError').classList.add('d-none');
    }

    function showError(error) {
        const btn = document.getElementById('btnGetLoc');
        btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Coba Lagi';
        btn.disabled = false;

        document.getElementById('gpsError').classList.remove('d-none');
        alert("Gagal mengambil lokasi. Pastikan izin lokasi browser diizinkan!");
    }
</script>
@endsection
