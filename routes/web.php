<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\StokDarahController;
use App\Http\Controllers\PermintaanDaruratController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =====================
// DASHBOARD
// =====================
Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// =====================
// STOK DARAH (DIPERBAIKI)
// =====================
// SEBELUMNYA (SALAH): Route::view('/stok-darah', 'stok')->name('admin.stok.darah');
// View berada di: resources/views/admin/stok.blade.php
// MAKA HARUS MENGGUNAKAN: 'admin.stok' (menggunakan titik untuk mengakses subfolder)

// OPSI 1: Menggunakan Route::view (LANGSUNG)

Route::view('/stok-darah', 'admin.stok')->name('admin.stok.darah');

// OPSI 2: Menggunakan Controller (REKOMENDASI - lebih fleksibel)
// Route::get('/stok-darah', [StokDarahController::class, 'index'])->name('admin.stok.darah');


// =====================
// PERMINTAAN DARURAT (DIPERBAIKI)
// =====================
// SEBELUMNYA (SALAH): Route::view('/permintaan-darurat', 'darurat')->name('admin.permintaan.darurat');
// View berada di: resources/views/admin/darurat.blade.php
// MAKA HARUS MENGGUNAKAN: 'admin.darurat'

// OPSI 1: Menggunakan Route::view (LANGSUNG)
Route::view('/permintaan-darurat', 'admin.darurat')->name('admin.permintaan.darurat');

// OPSI 2: Menggunakan Controller (REKOMENDASI)
// Route::get('/permintaan-darurat', [PermintaanDaruratController::class, 'index'])->name('admin.permintaan.darurat');


// =====================
// FEEDBACK USER
// =====================
Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');


// =====================
// ADMIN FEEDBACK
// =====================
Route::get('/admin/feedback', [FeedbackController::class, 'adminIndex'])->name('admin.feedback');


// =====================
// API
// =====================
Route::prefix('api')->group(function () {
    Route::get('/dashboard-data', [DashboardController::class, 'getData']);
    Route::post('/feedback', [FeedbackController::class, 'store']);
    Route::get('/feedback', [FeedbackController::class, 'getFeedback']);
    Route::delete('/feedback/{id}', [FeedbackController::class, 'destroy']);
});