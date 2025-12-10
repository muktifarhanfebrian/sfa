<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// Ini URL yang akan ditembak oleh tablet
// URL lengkapnya nanti jadi: http://domain-sfa-kamu.com/api/simpan-rating
Route::post('/simpan-rating', [RatingController::class, 'store']);
