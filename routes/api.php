<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StokDarahController;
use App\Http\Controllers\PermintaanDaruratController;
use App\Http\Controllers\FeedbackController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// API untuk Dashboard
Route::get('/dashboard-data', [DashboardController::class, 'getData']);

// API untuk Permintaan Darurat
Route::prefix('permintaan-darurat')->group(function () {
    Route::get('/', [PermintaanDaruratController::class, 'index']);
    Route::get('/summary', [PermintaanDaruratController::class, 'getSummary']);
    Route::post('/', [PermintaanDaruratController::class, 'store']);
    Route::get('/{id}', [PermintaanDaruratController::class, 'show']);
    Route::put('/{id}/proses', [PermintaanDaruratController::class, 'proses']);
    Route::delete('/{id}', [PermintaanDaruratController::class, 'destroy']);
});

// Route untuk stok darah
Route::prefix('stok-darah')->group(function () {
    Route::get('/', [StokDarahController::class, 'index']);
    Route::post('/', [StokDarahController::class, 'store']);
    Route::get('/total', [StokDarahController::class, 'getTotalStok']);
    Route::get('/statistik', [StokDarahController::class, 'getStatistik']);
    Route::get('/{id}', [StokDarahController::class, 'show']);
    Route::put('/{id}', [StokDarahController::class, 'update']);
    Route::delete('/{id}', [StokDarahController::class, 'destroy']);
});

// Route untuk Feedback (FIX: route ini sebelumnya tidak ada!)
Route::prefix('feedback')->group(function () {
    Route::get('/', [FeedbackController::class, 'getFeedback']);
    Route::post('/', [FeedbackController::class, 'store']);
    Route::get('/stats', [FeedbackController::class, 'getStats']);
    Route::put('/{id}/respond', [FeedbackController::class, 'respond']);
    Route::put('/{id}/status', [FeedbackController::class, 'updateStatus']);
    Route::delete('/{id}', [FeedbackController::class, 'destroy']);
});
