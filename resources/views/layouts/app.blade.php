<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SFA Bintang Interior')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0d6efd">
    <link rel="apple-touch-icon" href="{{ asset('logo-192.png') }}">

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(function() {
                    console.log('PWA Service Worker Registered');
                });
        }
    </script>
    <style>
        body {
            overflow-x: hidden;
        }

        #wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        #sidebar {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            background: #212529;
            color: #fff;
            transition: all 0.3s;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: #1a1e21;
            border-bottom: 1px solid #4b545c;
        }

        #sidebar ul.components {
            padding: 20px 0;
        }

        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 1rem;
            display: block;
            color: #adb5bd;
            text-decoration: none;
            border-left: 4px solid transparent;
        }

        #sidebar ul li a:hover {
            color: #fff;
            background: #343a40;
        }

        #sidebar ul li a.active {
            color: #fff;
            background: #343a40;
            border-left: 4px solid #0d6efd;
        }

        #content {
            width: 100%;
            min-height: 100vh;
            background-color: #f4f6f9;
        }

        /* Mobile: Sidebar sembunyi */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
                position: fixed;
                z-index: 999;
                height: 100%;
            }

            #sidebar.active {
                margin-left: 0;
            }

            #content {
                width: 100%;
            }

            .overlay {
                display: none;
                position: fixed;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 998;
            }

            .overlay.active {
                display: block;
            }
        }
    </style>
</head>

<body>

    <div id="wrapper">
        @auth
            <nav id="sidebar">
                <div class="sidebar-header">
                    <h4 class="fw-bold mb-0">
                        <i class="bi bi-stars text-primary"></i> SFA Bintang
                    </h4>
                    <small class="text-muted" style="font-size: 0.75rem;">Interior & Keramik System</small>
                </div>

                <ul class="list-unstyled components">

                    <li>
                        <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>

                    @if (Auth::check() && Auth::user()->role === 'sales')
                        <li>
                            <a href="{{ route('visits.plan') }}" class="{{ request()->is('visits/plan') ? 'active' : '' }}">
                                <i class="bi bi-calendar-plus me-2"></i> Rencana Visit
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('visits.create') }}"
                                class="{{ request()->is('visits/create') ? 'active' : '' }}">
                                <i class="bi bi-geo-alt me-2"></i> Check-in Visit
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customers.index') }}"
                                class="{{ request()->is('customers*') ? 'active' : '' }}">
                                <i class="bi bi-shop me-2"></i> Customer Saya
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('orders.create') }}"
                                class="{{ request()->is('orders/create') ? 'active' : '' }}">
                                <i class="bi bi-cart-plus me-2"></i> Buat Order
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('receivables.index') }}"
                                class="{{ request()->is('receivables*') ? 'active' : '' }}">
                                <i class="bi bi-cash-stack me-2"></i> Monitoring Piutang
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('visits.index') }}" class="{{ request()->is('visits') ? 'active' : '' }}">
                                <i class="bi bi-journal-check me-2"></i> Riwayat Visit
                            </a>
                        </li>
                    @endif

                    @if (Auth::check() && Auth::user()->role !== 'sales')
                        <li>
                            <a href="{{ route('products.index') }}"
                                class="{{ request()->is('products*') ? 'active' : '' }}">
                                <i class="bi bi-box-seam me-2"></i> Data Produk
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customers.index') }}"
                                class="{{ request()->is('customers*') ? 'active' : '' }}">
                                <i class="bi bi-shop me-2"></i> Customer
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('users.index') }}" class="{{ request()->is('users*') ? 'active' : '' }}">
                                <i class="bi bi-people me-2"></i> Kelola User
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('visits.index') }}" class="{{ request()->is('visits') ? 'active' : '' }}">
                                <i class="bi bi-map me-2"></i> Monitoring Sales
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('receivables.index') }}"
                                class="{{ request()->is('receivables*') ? 'active' : '' }}">
                                <i class="bi bi-cash-stack me-2"></i> Monitoring Piutang
                            </a>
                        </li>
                    @endif

                    <li>
                        <a href="{{ route('orders.index') }}" class="{{ request()->is('orders') ? 'active' : '' }}">
                            <i class="bi bi-receipt me-2"></i> Riwayat Order
                        </a>
                    </li>
                </ul>
            </nav>
        @endauth
        <div class="overlay" id="overlay"></div>

        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
                <div class="container-fluid">

                    @auth
                        <button type="button" id="sidebarCollapse" class="btn btn-primary d-md-none">
                            <i class="bi bi-list"></i>
                        </button>
                    @endauth

                    <span class="navbar-brand ms-3 fw-bold">@yield('title')</span>

                    <div class="ms-auto d-flex align-items-center"> @auth

                            <div class="dropdown me-3"> <a class="nav-link position-relative text-secondary" href="#"
                                    data-bs-toggle="dropdown">
                                    <i class="bi bi-bell fs-5"></i>

                                    @if (Auth::user()->unreadNotifications->count() > 0)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                            style="font-size: 0.6rem;">
                                            {{ Auth::user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                                    style="width: 320px; max-height: 400px; overflow-y: auto;">
                                    <li class="dropdown-header fw-bold bg-light py-2">Notifikasi Anda</li>

                                    @forelse(Auth::user()->unreadNotifications as $notification)
                                        <li>
                                            <a class="dropdown-item d-flex align-items-start p-3 border-bottom"
                                                href="{{ $notification->data['link'] ?? '#' }}?read={{ $notification->id }}">
                                                <div class="me-3 mt-1">
                                                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle text-primary">
                                                        <i
                                                            class="bi {{ $notification->data['icon'] ?? 'bi-info-circle' }}"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 small fw-bold">
                                                        {{ $notification->data['title'] ?? 'Info Baru' }}</h6>
                                                    <p class="mb-1 small text-muted text-wrap" style="line-height: 1.4;">
                                                        {{ $notification->data['message'] ?? '-' }}</p>
                                                    <small class="text-secondary"
                                                        style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                            </a>
                                        </li>
                                    @empty
                                        <li class="text-center py-4 text-muted small">
                                            <i class="bi bi-bell-slash d-block fs-4 mb-2 opacity-50"></i>
                                            Tidak ada notifikasi baru.
                                        </li>
                                    @endforelse

                                    @if (Auth::user()->unreadNotifications->count() > 0)
                                        <li><a class="dropdown-item text-center small text-primary fw-bold py-2 bg-light"
                                                href="{{ route('notifications.markRead') }}">Tandai Semua Dibaca</a></li>
                                    @endif
                                </ul>
                            </div>
                            <div class="dropdown">
                                <a href="#"
                                    class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark"
                                    id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if (Auth::user()->photo)
                                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" width="36"
                                            height="36" class="rounded-circle me-2 border object-fit-cover">
                                    @else
                                        <div class="bg-secondary rounded-circle text-white d-flex justify-content-center align-items-center me-2"
                                            style="width: 36px; height: 36px;">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="d-none d-sm-inline fw-semibold small">{{ Auth::user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i
                                                class="bi bi-person me-2"></i>Profil Saya</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger"><i
                                                    class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>

                        @endauth
                    </div>
                </div>
            </nav>

            <div class="container-fluid p-4">
                @yield('content')
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const overlay = document.getElementById('overlay');
            const btn = document.getElementById('sidebarCollapse');

            function toggleSidebar() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            }

            btn.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);
        });
    </script>

</body>

</html>
