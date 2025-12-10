<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schedule;

// Jalankan perintah invoice:remind setiap hari jam 08:00 Pagi
Schedule::command('invoice:remind')->dailyAt('08:00');

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

    // Route Manajemen User
    Route::resource('users', App\Http\Controllers\UserController::class);

    // Route Resource (Otomatis buat index, create, store, edit, update, destroy)
    Route::resource('products', \App\Http\Controllers\ProductController::class);

    // Route Manajemen Piutang
    Route::get('/receivables', [App\Http\Controllers\ReceivableController::class, 'index'])->name('receivables.index');

    // Route Export Piutang ke Excel
    Route::get('/receivables/export', [App\Http\Controllers\ReceivableController::class, 'export'])->name('receivables.export');

    // Route Arsip Piutang Lunas
    Route::get('/receivables/completed', [App\Http\Controllers\ReceivableController::class, 'completed'])->name('receivables.completed');

    // Route Notifikasi Pengingat Piutang
    Route::post('/receivables/remind', [App\Http\Controllers\ReceivableController::class, 'sendReminders'])->name('receivables.remind');

    // Matikan Red Dot Notifikasi
    Route::get('/notifications/read', function () {
        // Menggunakan Auth::user() lebih standar
        // Jika masih merah, abaikan saja (itu False Positive), kodenya tetap jalan 100%
        Auth::user()->unreadNotifications->markAsRead();

        return back();
    })->name('notifications.markRead');

    // Route Transaksi
    Route::get('/orders/create', [App\Http\Controllers\OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');

    // Route untuk Proses Pembayaran
    Route::post('/orders/{order}/pay', [App\Http\Controllers\OrderController::class, 'pay'])->name('orders.pay');

    // Route Visit / Kunjungan
    Route::get('/visits/create', [App\Http\Controllers\VisitController::class, 'create'])->name('visits.create');
    Route::post('/visits', [App\Http\Controllers\VisitController::class, 'store'])->name('visits.store');
    Route::get('/visits', [App\Http\Controllers\VisitController::class, 'index'])->name('visits.index'); // Untuk lihat riwayat

    // Route Visit Planning
    Route::get('/visits/plan', [App\Http\Controllers\VisitController::class, 'createPlan'])->name('visits.plan');
    Route::post('/visits/plan', [App\Http\Controllers\VisitController::class, 'storePlan'])->name('visits.storePlan');

    // Route Visit Execution (Check-in dari rencana)
    Route::get('/visits/{visit}/perform', [App\Http\Controllers\VisitController::class, 'perform'])->name('visits.perform');
    Route::put('/visits/{visit}', [App\Http\Controllers\VisitController::class, 'update'])->name('visits.update');

    // Route Manajemen Customer / Toko
    Route::resource('customers', App\Http\Controllers\CustomerController::class);

    // Route khusus untuk tombol "Proses Order"
    Route::post('/orders/{order}/process', [App\Http\Controllers\OrderController::class, 'markAsProcessed'])->name('orders.markProcessed');

    // Route Profil
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
});
