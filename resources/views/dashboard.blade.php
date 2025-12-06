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

                                <div class="progress" style="height: 10px; background-color: rgba(255,255,255,0.3);">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                        style="width: {{ $visitPercentage }}%" aria-valuenow="{{ $visitPercentage }}"
                                        aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="mt-2 d-block">{{ number_format($visitPercentage, 0) }}% Tercapai</small>
                            </div>

                            <div class="col-md-4 text-end d-none d-md-block">
                                <i class="bi bi-trophy-fill text-white opacity-25"
                                    style="font-size: 8rem; position: absolute; right: 20px; top: -20px;"></i>

                                <a href="{{ route('visits.create') }}"
                                    class="btn btn-light text-primary fw-bold shadow-sm mt-3">
                                    <i class="bi bi-geo-alt-fill"></i> Check-in Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row g-3">

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-4 border-primary h-100">
                        <div class="card-body">
                            <div class="text-muted small fw-bold text-uppercase mb-1">Order Hari Ini</div>
                            <div class="d-flex align-items-center">
                                <h2 class="fw-bold text-gray-800 mb-0">{{ $todayOrders }}</h2>
                                <i class="bi bi-bag-check ms-auto fs-3 text-primary-emphasis opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-4 border-success h-100">
                        <div class="card-body">
                            <div class="text-muted small fw-bold text-uppercase mb-1">Total Transaksi</div>
                            <div class="d-flex align-items-center">
                                <h2 class="fw-bold text-gray-800 mb-0">{{ $totalOrders }}</h2>
                                <i class="bi bi-receipt ms-auto fs-3 text-success-emphasis opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-4 border-info h-100">
                        <div class="card-body">
                            <div class="text-muted small fw-bold text-uppercase mb-1">Total Omset</div>
                            <div class="d-flex align-items-center">
                                <h4 class="fw-bold text-gray-800 mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-4 border-warning h-100">
                        <div class="card-body">
                            <div class="text-muted small fw-bold text-uppercase mb-1">Stok Menipis</div>
                            <div class="d-flex align-items-center">
                                <h2 class="fw-bold text-danger mb-0">{{ $lowStockCount }}</h2>
                                <small class="ms-2 text-muted">Item</small>
                                <i class="bi bi-exclamation-triangle ms-auto fs-3 text-warning-emphasis opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-4 p-5 text-center bg-white rounded shadow-sm border border-light-subtle">
                <div class="mb-3">
                    <i class="bi bi-bar-chart-fill text-primary opacity-25" style="font-size: 5rem;"></i>
                </div>

                <h5 class="fw-bold text-secondary">Analisis Penjualan Belum Tersedia</h5>
                <p class="text-muted mx-auto" style="max-width: 500px;">
                    Saat ini data grafik belum dikonfigurasi. Anda dapat melihat riwayat transaksi detail melalui menu
                    "Riwayat Order".
                </p>

                <a href="{{ route('orders.index') }}" class="btn btn-outline-primary mt-2">
                    <i class="bi bi-file-earmark-text"></i> Lihat Laporan Lengkap
                </a>
            </div>

        </div>
    </div>
@endsection
