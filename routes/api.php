<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StokDarahController;
use App\Http\Controllers\PermintaanDaruratController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// API untuk Dashboard
Route::get('/dashboard-data', [DashboardController::class, 'getData']);

// API untuk Stok Darah (CRUD) - PASTIKAN INI ADA
Route::prefix('stok-darah')->group(function () {
    Route::get('/', [StokDarahController::class, 'index']);           // GET /api/stok-darah
    Route::post('/', [StokDarahController::class, 'store']);          // POST /api/stok-darah
    Route::get('/total', [StokDarahController::class, 'getTotalStok']); // GET /api/stok-darah/total
    Route::get('/{id}', [StokDarahController::class, 'show']);        // GET /api/stok-darah/{id}
    Route::post('/{id}', [StokDarahController::class, 'update']);     // POST /api/stok-darah/{id} (with _method=PUT)
    Route::delete('/{id}', [StokDarahController::class, 'destroy']);  // DELETE /api/stok-darah/{id}
});

// API untuk Permintaan Darurat
Route::prefix('permintaan-darurat')->group(function () {
    Route::get('/', [PermintaanDaruratController::class, 'index']);
    Route::get('/summary', [PermintaanDaruratController::class, 'getSummary']);
    Route::post('/', [PermintaanDaruratController::class, 'store']);
    Route::get('/{id}', [PermintaanDaruratController::class, 'show']);
    Route::put('/{id}/proses', [PermintaanDaruratController::class, 'proses']);
    Route::delete('/{id}', [PermintaanDaruratController::class, 'destroy']);
});