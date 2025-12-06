<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Redirect halaman depan ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route untuk Guest (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Route untuk User yang Sudah Login (Auth)
Route::middleware('auth')->group(function () {
    // Dashboard Sementara
    // Route Dashboard yang baru
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout (Harus POST demi keamanan)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route Resource (Otomatis buat index, create, store, edit, update, destroy)
    Route::resource('products', \App\Http\Controllers\ProductController::class);

    // Route Transaksi
    Route::get('/orders/create', [App\Http\Controllers\OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');

    // Route Visit / Kunjungan
    Route::get('/visits/create', [App\Http\Controllers\VisitController::class, 'create'])->name('visits.create');
    Route::post('/visits', [App\Http\Controllers\VisitController::class, 'store'])->name('visits.store');
    Route::get('/visits', [App\Http\Controllers\VisitController::class, 'index'])->name('visits.index'); // Untuk lihat riwayat

    // Route khusus untuk tombol "Proses Order"
    Route::post('/orders/{order}/process', [App\Http\Controllers\OrderController::class, 'markAsProcessed'])->name('orders.markProcessed');
});

