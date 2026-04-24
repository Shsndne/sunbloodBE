<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StokDarahController;
use App\Http\Controllers\PermintaanDaruratController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes — Sunblood
|--------------------------------------------------------------------------
*/

// ── PUBLIC PAGES ──────────────────────────────────────────────────────────
Route::get('/', [PublicController::class, 'landing'])->name('home');
Route::get('/konsultasi', [PublicController::class, 'konsultasi'])->name('konsultasi');
Route::get('/stok-darah', [PublicController::class, 'stokDarah'])->name('stok-darah');
Route::get('/darurat', [PublicController::class, 'darurat'])->name('darurat');
Route::get('/feedback', [PublicController::class, 'feedback'])->name('feedback.page');

// ── AUTH ROUTES (Laravel Breeze) ──────────────────────────────────────────
require __DIR__.'/auth.php';

// ── PROFILE (Auth Required) ───────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── ADMIN ROUTES (Auth + Admin + IP Restriction) ──────────────────────────
// Hapus 'ip.restriction' dari middleware jika sedang development dari berbagai IP
Route::middleware(['auth', 'admin', 'ip.restriction'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', fn() => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Stok Darah CRUD
    Route::get('/stok-darah',               [DashboardController::class, 'stokIndex'])->name('stok.index');
    Route::get('/stok-darah/tambah',        [DashboardController::class, 'stokCreate'])->name('stok.create');
    Route::post('/stok-darah',              [DashboardController::class, 'stokStore'])->name('stok.store');
    Route::get('/stok-darah/{stok}/edit',   [DashboardController::class, 'stokEdit'])->name('stok.edit');
    Route::put('/stok-darah/{stok}',        [DashboardController::class, 'stokUpdate'])->name('stok.update');
    Route::delete('/stok-darah/{stok}',     [DashboardController::class, 'stokDestroy'])->name('stok.destroy');

    // Permintaan Darurat
    Route::get('/darurat',                          [DashboardController::class, 'permintaanIndex'])->name('darurat.index');
    Route::get('/darurat/{permintaan}',             [DashboardController::class, 'permintaanShow'])->name('darurat.show');
    Route::patch('/darurat/{permintaan}/status',    [DashboardController::class, 'permintaanUpdateStatus'])->name('darurat.status');
    Route::delete('/darurat/{permintaan}',          [DashboardController::class, 'permintaanDestroy'])->name('darurat.destroy');

    // Feedback
    Route::get('/feedback',                     [DashboardController::class, 'feedbackIndex'])->name('feedback.index');
    Route::post('/feedback/{feedback}/balas',   [DashboardController::class, 'feedbackBalas'])->name('feedback.balas');
    Route::delete('/feedback/{feedback}',       [DashboardController::class, 'feedbackDestroy'])->name('feedback.destroy');

    // Users
    Route::get('/users',            [DashboardController::class, 'usersIndex'])->name('users.index');
    Route::delete('/users/{user}',  [DashboardController::class, 'usersDestroy'])->name('users.destroy');
});
