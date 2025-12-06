@extends('layouts.app')

@section('title', 'Laporan Kunjungan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold text-gray-800">Laporan Kunjungan Lapangan</h1>

    @if(Auth::user()->role === 'sales')
    <a href="{{ route('visits.create') }}" class="btn btn-primary">
        <i class="bi bi-geo-alt-fill"></i> Check-in Baru
    </a>
    @endif
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Sales</th>
                        <th>Toko / Customer</th>
                        <th>Bukti Foto</th>
                        <th>Lokasi (GPS)</th>
                        <th>Laporan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visits as $visit)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $visit->created_at->format('d M Y') }}</div>
                            <small class="text-muted">{{ $visit->created_at->format('H:i') }} WIB</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                    {{ substr($visit->user->name, 0, 1) }}
                                </div>
                                <div>{{ $visit->user->name }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ $visit->customer->name }}</span>
                            <br>
                            <small class="text-muted">{{ Str::limit($visit->customer->address, 20) }}</small>
                        </td>
                        <td>
                            @if($visit->photo_path)
                                <a href="{{ asset('storage/' . $visit->photo_path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $visit->photo_path) }}"
                                         class="rounded border shadow-sm"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($visit->latitude && $visit->longitude)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $visit->latitude }},{{ $visit->longitude }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-map"></i> Lihat Peta
                                </a>
                            @else
                                <span class="badge bg-secondary">No GPS</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted fst-italic">
                                "{{ $visit->notes ?? 'Tidak ada catatan' }}"
                            </small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-clipboard-x fs-1 d-block mb-2"></i>
                            Belum ada data kunjungan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $visits->links() }}
        </div>
    </div>
</div>
@endsection
