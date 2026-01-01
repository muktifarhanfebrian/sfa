@extends('layouts.app')

@section('title', 'Dashboard Sales')

@section('content')
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-primary">Dashboard Sales</h4>
                <p class="text-muted mb-0">Halo {{ $user->name }}, semangat kejar target hari ini!</p>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill">
                    <i class="bi bi-calendar-event me-1"></i> {{ date('d M Y') }}
                </span>
            </div>
        </div>

        {{-- 1. INFO PLAFON KREDIT (Sisa Limit) --}}
        @if ($limitQuota > 0)
            @if ($isCritical)
                <div class="alert alert-danger shadow-sm d-flex align-items-center justify-content-between" role="alert">
                    <div>
                        <h5 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Limit Menipis!
                        </h5>
                        <p class="mb-0 small">
                            Sisa limit: <strong>Rp {{ number_format($remaining, 0, ',', '.') }}</strong>
                            (Terpakai: Rp {{ number_format($usedCredit, 0, ',', '.') }}).
                        </p>
                    </div>
                    <button type="button" class="btn btn-light text-danger fw-bold btn-sm" data-bs-toggle="modal"
                        data-bs-target="#requestLimitModal">
                        <i class="bi bi-arrow-up-circle me-1"></i> Minta Limit
                    </button>
                </div>
            @else
                <div class="card shadow-sm border-0 border-start border-4 border-success mb-4">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-uppercase text-muted fw-bold">Sisa Plafon Kredit</small>
                                <h4 class="fw-bold text-success mb-0">Rp {{ number_format($remaining, 0, ',', '.') }}</h4>
                            </div>
                            <i class="bi bi-wallet2 fs-1 text-gray-300 opacity-25"></i>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        {{-- 2. WIDGET TARGET KUNJUNGAN (HARIAN) --}}
        <div class="card shadow-sm border-0 mb-4 bg-primary text-white overflow-hidden">
            <div class="card-body p-4 position-relative">
                <div class="row align-items-center position-relative z-1">
                    <div class="col-8">
                        <h5 class="fw-bold mb-1">Target Kunjungan Hari Ini</h5>
                        <div class="d-flex align-items-end mb-2">
                            <h1 class="display-4 fw-bold mb-0 me-2">{{ $todayVisits }}</h1>
                            <span class="fs-5 mb-2 opacity-75">/ {{ $visitTarget }} Toko</span>
                        </div>

                        <div class="progress" style="height: 8px; background-color: rgba(255,255,255,0.3);">
                            <div class="progress-bar bg-warning" role="progressbar"
                                style="width: {{ min($visitPercentage, 100) }}%"></div>
                        </div>
                        <small class="mt-2 d-block">{{ round($visitPercentage) }}% Tercapai</small>
                    </div>
                    <div class="col-4 text-end">
                        <i class="bi bi-geo-alt-fill opacity-25" style="font-size: 5rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- 3. RENCANA KUNJUNGAN --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-check text-primary me-2"></i>Rencana Visit</h6>
                        <a href="{{ route('visits.plan') }}" class="btn btn-sm btn-outline-primary">+ Tambah</a>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse($plannedVisits as $plan)
                            @php
                                $borderColor = 'primary';
                                if ($plan->status == 'in_progress') {
                                    $borderColor = 'warning';
                                }
                                if ($plan->status == 'completed') {
                                    $borderColor = 'success';
                                }
                            @endphp
                            <div class="list-group-item border-0 border-start border-4 border-{{ $borderColor }} py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $plan->customer->name }}</h6>
                                        <small class="text-muted"><i
                                                class="bi bi-geo-alt me-1"></i>{{ Str::limit($plan->customer->address, 30) }}</small>
                                    </div>
                                    <div>
                                        @if ($plan->status == 'planned')
                                            <form action="{{ route('visits.checkIn', $plan->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-primary btn-sm rounded-pill px-3">Check In</button>
                                            </form>
                                        @elseif($plan->status == 'in_progress')
                                            <a href="{{ route('visits.perform', $plan->id) }}"
                                                class="btn btn-warning btn-sm rounded-pill px-3 fw-bold">Check Out</a>
                                        @else
                                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Selesai</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-x fs-1 opacity-25"></i>
                                <p class="mt-2">Belum ada rencana kunjungan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- 4. PENCAPAIAN OMSET --}}
            {{-- WIDGET OMSET (MOBILE OPTIMIZED - CLEAN) --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                <div class="card-body p-3">

                    {{-- HEADER: Icon + Title + Persentase Badge --}}
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            {{-- Icon Lingkaran (Ukuran pas untuk jempol) --}}
                            <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle me-3 flex-shrink-0"
                                style="width: 40px; height: 40px;">
                                <i class="bi bi-trophy-fill fs-5"></i>
                            </div>
                            {{-- Text Label --}}
                            <div>
                                <h6 class="text-muted text-uppercase fw-bold mb-0"
                                    style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    Omset Bulan Ini
                                </h6>
                                <div class="small text-success fw-bold" style="font-size: 0.75rem;">
                                    <i class="bi bi-graph-up-arrow me-1"></i> On Track
                                </div>
                            </div>
                        </div>
                        {{-- Badge Persen di Kanan Atas --}}
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">
                            9%
                        </span>
                    </div>

                    {{-- ANGKA UTAMA (Besar & Jelas) --}}
                    <h3 class="fw-bold text-dark mb-3 mt-1" style="font-size: 1.5rem;">
                        Rp {{ number_format(4375000, 0, ',', '.') }}
                    </h3>

                    {{-- PROGRESS BAR SECTION --}}
                    <div>
                        {{-- Bar --}}
                        <div class="progress mb-2" style="height: 8px; background-color: #f1f5f9; border-radius: 10px;">
                            <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: 9%;"
                                aria-valuenow="9" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        {{-- Info Target (Kiri Kanan Rata) --}}
                        <div class="d-flex justify-content-between align-items-center text-muted"
                            style="font-size: 0.7rem;">
                            <span>Target: <strong>Rp 50.000.000</strong></span>
                            <span>Kurang: Rp 45.625.000</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- MODAL REQUEST LIMIT --}}
        @include('dashboard.partials.modal_request_limit')

    </div>

    {{-- SCRIPT CHART --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('salesChart');
            if (ctx) {
                new Chart(ctx.getContext('2d'), {
                    type: 'line', // Ganti jadi Line biar keren di dalam card
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt',
                            'Nov', 'Des'
                        ],
                        datasets: [{
                            label: 'Omset',
                            data: @json($chartData),
                            borderColor: '#fff',
                            backgroundColor: 'rgba(255,255,255,0.2)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 0
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                display: false
                            },
                            y: {
                                display: false
                            }
                        },
                        maintainAspectRatio: false
                    }
                });
            }
        });
    </script>
@endsection
