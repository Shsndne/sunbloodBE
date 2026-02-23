<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route ke Halaman Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Route ke Halaman Stok Darah
Route::view('/stok-darah', 'stok')->name('stok.darah');

// Route ke Halaman Permintaan Darurat
Route::view('/permintaan-darurat', 'darurat')->name('permintaan.darurat');

// Atau jika pakai controller
// Route::get('/permintaan-darurat', [PermintaanController::class, 'index'])->name('permintaan.darurat');
// Route ke Halaman Permintaan Daruratphp artisan migrate
Route::view('/permintaan-darurat', 'darurat')->name('permintaan.darurat');