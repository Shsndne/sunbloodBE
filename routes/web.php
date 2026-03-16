<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\DashboardController;

// ─── HALAMAN PUBLIK ───────────────────────────────
Route::get('/', [PublicController::class, 'index'])->name('home');

Route::get('/stok-darah', function () {
    return view('public.stok-darah');
})->name('stok-darah');

Route::get('/konsultasi', function () {
    return view('public.konsultasi');
})->name('konsultasi');

Route::get('/darurat', [PublicController::class, 'formDarurat'])->name('darurat');
Route::post('/darurat', [PublicController::class, 'simpanDarurat'])->name('darurat.simpan');
Route::get('/darurat/sukses/{kode}', [PublicController::class, 'suksessDarurat'])->name('darurat.sukses');

Route::get('/feedback', function () {
    return view('public.feedback');
})->name('feedback.page');
Route::post('/feedback', [PublicController::class, 'simpanFeedback'])->name('feedback.simpan');

Route::get('/regis', function () {
    return view('public.regis');
})->name('regis');

// ─── AUTH ─────────────────────────────────────────
require __DIR__.'/auth.php';

// ─── DASHBOARD ADMIN ──────────────────────────────
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/stok',             [DashboardController::class, 'stokIndex'])->name('stok.index');
    Route::get('/stok/tambah',      [DashboardController::class, 'stokCreate'])->name('stok.create');
    Route::post('/stok',            [DashboardController::class, 'stokStore'])->name('stok.store');
    Route::get('/stok/{stok}/edit', [DashboardController::class, 'stokEdit'])->name('stok.edit');
    Route::put('/stok/{stok}',      [DashboardController::class, 'stokUpdate'])->name('stok.update');
    Route::delete('/stok/{stok}',   [DashboardController::class, 'stokDestroy'])->name('stok.destroy');

    Route::get('/permintaan',                      [DashboardController::class, 'permintaanIndex'])->name('permintaan.index');
    Route::get('/permintaan/{permintaan}',          [DashboardController::class, 'permintaanShow'])->name('permintaan.show');
    Route::patch('/permintaan/{permintaan}/status', [DashboardController::class, 'permintaanUpdateStatus'])->name('permintaan.status');
    Route::delete('/permintaan/{permintaan}',       [DashboardController::class, 'permintaanDestroy'])->name('permintaan.destroy');

    Route::get('/feedback',                   [DashboardController::class, 'feedbackIndex'])->name('feedback.index');
    Route::post('/feedback/{feedback}/balas', [DashboardController::class, 'feedbackBalas'])->name('feedback.balas');
    Route::delete('/feedback/{feedback}',     [DashboardController::class, 'feedbackDestroy'])->name('feedback.destroy');
});