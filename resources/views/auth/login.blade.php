@extends('layouts.app')

@section('title', 'Login - SFA Bintang')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5 col-lg-4">

        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">SFA Bintang</h2>
            <p class="text-muted">Silakan masuk untuk memulai.</p>
        </div>

        <div class="card shadow border-0 rounded-4">
            <div class="card-body p-4">

                @if ($errors->any())
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ url('/login') }}" method="POST">
                    @csrf <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-lg"
                               value="{{ old('email') }}" placeholder="admin@bintang.com" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg"
                               placeholder="********" required>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">Masuk Aplikasi</button>
                    </div>
                </form>

            </div>
        </div>

        <div class="text-center mt-4">
            <small class="text-muted">&copy; {{ date('Y') }} Bintang Interior & Keramik</small>
        </div>

    </div>
</div>
@endsection
