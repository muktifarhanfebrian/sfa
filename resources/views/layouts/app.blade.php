<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SFA Bintang Interior')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">SFA Bintang</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('products*') ? 'active' : '' }}"
                                href="{{ route('products.index') }}">
                                Produk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('orders*') ? 'active' : '' }}"
                                href="{{ route('orders.create') }}">
                                Order Baru
                            </a>
                        </li>
                        @if (Auth::user()->role === 'admin' || Auth::user()->role === 'manager')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('visits*') ? 'active' : '' }}"
                                    href="{{ route('visits.index') }}">
                                    Monitoring Sales
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('visits*') ? 'active' : '' }}"
                                    href="{{ route('visits.index') }}">
                                    Kunjungan Saya
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle btn btn-light text-dark px-3 rounded-pill" href="#"
                                role="button" data-bs-toggle="dropdown">
                                Halo, {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

</body>

</html>
