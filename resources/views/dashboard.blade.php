@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 mb-4">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="fw-bold text-primary">Dashboard Overview</h4>
                    <p class="text-muted">Halo {{ Auth::user()->name }}, inilah performa toko hari ini.</p>
                </div>
                <div>
                    <a href="{{ route('orders.create') }}" class="btn btn-primary shadow-sm">
                        <i class="bi bi-plus-circle"></i> Buat Order Baru
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (Auth::user()->role === 'sales')
                <div class="card shadow-sm border-0 mb-4 bg-primary text-white overflow-hidden">
                    <div class="card-body p-4 position-relative">
                        <div class="row align-items-center position-relative z-1">
                            <div class="col-md-8">
                                <h4 class="fw-bold mb-1">Target Kunjungan Harian</h4>
                                <p class="mb-3 text-white-50">Ayo kejar targetmu hari ini!</p>

                                <div class="d-flex align-items-end mb-2">
                                    <h1 class="display-4 fw-bold mb-0 me-2">{{ $todayVisits }}</h1>
                                    <span class="fs-5 mb-2">/ {{ $visitTarget }} Toko</span>
                                </div>

                                @php
                                    $progressBarColor = $visitPercentage >= 100 ? 'bg-success' : 'bg-warning';
                                    // Logika Lebar: Mentok di 100% biar gak bocor layoutnya
                                    $progressBarWidth = min($visitPercentage, 100);
                                @endphp

                                <div class="progress" style="height: 10px; background-color: rgba(255,255,255,0.3);">
                                    <div class="progress-bar {{ $progressBarColor }}" role="progressbar"
                                        style="width: {{ $progressBarWidth }}%" aria-valuenow="{{ $visitPercentage }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>

                                <small class="mt-2 d-block">
                                    @if ($visitPercentage >= 100)
                                        🎉 Luar Biasa! {{ number_format($visitPercentage, 0) }}% Tercapai
                                    @else
                                        {{ number_format($visitPercentage, 0) }}% Tercapai
                                    @endif
                                </small>
                            </div>

                            <div class="col-md-4 text-end d-none d-md-block">
                                <i class="bi bi-trophy-fill text-white opacity-25"
                                    style="font-size: 8rem; position: absolute; right: 20px; top: -20px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row g-3">
                @if (Auth::user()->role === 'sales')
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-calendar-check text-primary"></i> Jadwal Kunjungan Hari
                                Ini</h6>
                            <a href="{{ route('visits.plan') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-plus"></i> Tambah Rencana
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($plannedVisits as $plan)
                                    <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                                        <div>
                                            <h6 class="mb-1 fw-bold">{{ $plan->customer->name }}</h6>
                                            <small class="text-muted d-block"><i class="bi bi-geo-alt"></i>
                                                {{ Str::limit($plan->customer->address, 30) }}</small>
                                            @if ($plan->notes)
                                                <small class="text-info fst-italic">"{{ $plan->notes }}"</small>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('visits.perform', $plan->id) }}"
                                                class="btn btn-success btn-sm fw-bold px-3">
                                                Check-in <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted">
                                        <p class="mb-0">Tidak ada rencana kunjungan hari ini.</p>
                                        <small>Klik tombol "Tambah Rencana" untuk membuat jadwal.</small>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif
                @if (Auth::user()->role !== 'sales')
                    <div class="row g-3 mb-3">
                        <div class="col-md-4 col-sm-6">
                            <div class="card shadow-sm border-0 border-start border-4 border-primary h-100">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Order Hari Ini</h6>
                                        <h2 class="fw-bold text-dark mb-0">{{ $todayOrders }}</h2>
                                    </div>
                                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                        <i class="bi bi-bag-check fs-2 text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <div class="card shadow-sm border-0 border-start border-4 border-success h-100">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Total Transaksi</h6>
                                        <h2 class="fw-bold text-dark mb-0">{{ $totalOrders }}</h2>
                                    </div>
                                    <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                        <i class="bi bi-receipt fs-2 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="card shadow-sm border-0 border-start border-4 border-warning h-100">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-muted small fw-bold text-uppercase mb-1">Stok Menipis</h6>
                                        <h2 class="fw-bold text-danger mb-0">{{ $lowStockCount }}</h2>
                                        <small class="text-muted">Item perlu restock</small>
                                    </div>
                                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                        <i class="bi bi-exclamation-triangle fs-2 text-warning"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4 col-sm-6">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                            <i class="bi bi-graph-up-arrow fs-4 text-primary"></i>
                                        </div>
                                        <h6 class="text-muted small fw-bold text-uppercase mb-0">Total Penjualan</h6>
                                    </div>
                                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                    </h3>
                                    <small class="text-muted" style="font-size: 0.75rem;">Nilai transaksi kotor
                                        (Gross)</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                            <i class="bi bi-wallet2 fs-4 text-success"></i>
                                        </div>
                                        <h6 class="text-muted small fw-bold text-uppercase mb-0">Uang Diterima</h6>
                                    </div>
                                    <h3 class="fw-bold text-success mb-0">Rp
                                        {{ number_format($cashReceived, 0, ',', '.') }}
                                    </h3>
                                    <small class="text-muted" style="font-size: 0.75rem;">Cashflow Masuk
                                        (Lunas/Cicil)</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-danger bg-opacity-10 p-2 rounded me-3">
                                            <i class="bi bi-journal-x fs-4 text-danger"></i>
                                        </div>
                                        <h6 class="text-muted small fw-bold text-uppercase mb-0">Sisa Piutang</h6>
                                    </div>
                                    <h3 class="fw-bold text-danger mb-0">Rp
                                        {{ number_format($totalReceivable, 0, ',', '.') }}
                                    </h3>
                                    <small class="text-muted" style="font-size: 0.75rem;">Uang belum tertagih</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-bar-chart-line text-primary"></i> Grafik Penjualan
                                Bulanan
                                ({{ date('Y') }})</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="salesChart" style="max-height: 400px;"></canvas>
                        </div>
                    </div>
                @endif
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const ctx = document.getElementById('salesChart').getContext('2d');

                        // Ambil data dari Controller Laravel (Blade to JS)
                        const labels = @json($chartLabels);
                        const data = @json($chartData);

                        new Chart(ctx, {
                            type: 'bar', // Bisa ganti 'line' kalau mau garis
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Total Omset (Rp)',
                                    data: data,
                                    backgroundColor: 'rgba(54, 162, 235, 0.6)', // Warna Batang Biru
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1,
                                    borderRadius: 5
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            // Format Rupiah di Sumbu Y
                                            callback: function(value) {
                                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                            }
                                        }
                                    }
                                },
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            // Format Rupiah di Tooltip (saat mouse hover)
                                            label: function(context) {
                                                let value = context.raw;
                                                return ' Omset: Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>
                @if (Auth::user()->role !== 'sales')
                    <div class="row mt-4">

                        <div class="col-lg-7 mb-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white py-3">
                                    <h5 class="mb-0 fw-bold"><i class="bi bi-trophy text-warning"></i> Top Sales Bulan Ini
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="leaderboardChart" style="max-height: 300px;"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5 mb-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white py-3">
                                    <h5 class="mb-0 fw-bold"><i class="bi bi-list-stars text-primary"></i> Efektivitas
                                        Sales
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-4">Nama</th>
                                                    <th class="text-center">Visit</th>
                                                    <th class="text-end pe-4">Omset</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($topSales as $index => $sales)
                                                    <tr>
                                                        <td class="ps-4">
                                                            <div class="d-flex align-items-center">
                                                                @if ($index == 0)
                                                                    <span class="me-2 fs-5">🥇</span>
                                                                @elseif($index == 1)
                                                                    <span class="me-2 fs-5">🥈</span>
                                                                @elseif($index == 2)
                                                                    <span class="me-2 fs-5">🥉</span>
                                                                @else
                                                                    <span class="me-2 text-muted fw-bold"
                                                                        style="width: 24px; text-align:center;">{{ $index + 1 }}</span>
                                                                @endif

                                                                <span class="fw-semibold">{{ $sales->name }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <span
                                                                class="badge bg-info text-dark">{{ $sales->visits_count }}
                                                                Toko</span>
                                                        </td>
                                                        <td class="text-end pe-4 fw-bold text-success">
                                                            Rp
                                                            {{ number_format($sales->orders_sum_total_price, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if (count($topSales) == 0)
                                                    <tr>
                                                        <td colspan="3" class="text-center py-4 text-muted">Belum ada
                                                            data
                                                            penjualan bulan ini.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            // Cek apakah elemen ada (karena hanya muncul di Admin)
                            const ctxLeaderboard = document.getElementById('leaderboardChart');

                            if (ctxLeaderboard) {
                                new Chart(ctxLeaderboard.getContext('2d'), {
                                    type: 'bar',
                                    data: {
                                        labels: @json($salesNames),
                                        datasets: [{
                                            label: 'Total Omset (Rp)',
                                            data: @json($salesRevenue),
                                            backgroundColor: [
                                                'rgba(255, 206, 86, 0.7)', // Juara 1 (Kuning Emas)
                                                'rgba(192, 192, 192, 0.7)', // Juara 2 (Silver)
                                                'rgba(205, 127, 50, 0.7)', // Juara 3 (Perunggu)
                                                'rgba(54, 162, 235, 0.5)', // Sisanya Biru
                                                'rgba(54, 162, 235, 0.5)'
                                            ],
                                            borderColor: 'rgba(0,0,0,0.1)',
                                            borderWidth: 1,
                                            borderRadius: 5
                                        }]
                                    },
                                    options: {
                                        indexAxis: 'y', // PENTING: Grafik Horizontal biar nama panjang muat
                                        responsive: true,
                                        plugins: {
                                            legend: {
                                                display: false
                                            } // Sembunyikan legenda biar bersih
                                        },
                                        scales: {
                                            x: {
                                                ticks: {
                                                    callback: function(value) {
                                                        return 'Rp ' + new Intl.NumberFormat('id-ID', {
                                                            notation: "compact"
                                                        }).format(value);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            }
                        });
                    </script>
                @endif
            </div>
        </div>
    @endsection
