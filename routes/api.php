<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StokDarahController;
use App\Http\Controllers\PermintaanDaruratController;
use App\Http\Controllers\FeedbackController;

/*
|--------------------------------------------------------------------------
| API Routes — Sunblood
| Dipanggil oleh JavaScript frontend (availability.js, emergency.js, dll)
|--------------------------------------------------------------------------
*/

// ── STOK DARAH ──────────────────────────────────────────────────────────
Route::prefix('stok-darah')->group(function () {
    Route::get('/',             [StokDarahController::class, 'index']);          // GET /api/stok-darah
    Route::get('/ringkasan/total', [StokDarahController::class, 'totalRingkasan']); // GET /api/stok-darah/ringkasan/total
    Route::get('/{id}',         [StokDarahController::class, 'show']);           // GET /api/stok-darah/{id}
    Route::post('/',            [StokDarahController::class, 'store']);          // POST /api/stok-darah
    Route::put('/{id}',         [StokDarahController::class, 'update']);         // PUT /api/stok-darah/{id}
    Route::delete('/{id}',      [StokDarahController::class, 'destroy']);        // DELETE /api/stok-darah/{id}
});

// ── PERMINTAAN DARURAT ───────────────────────────────────────────────────
Route::prefix('permintaan-darurat')->group(function () {
    Route::get('/',                     [PermintaanDaruratController::class, 'index']);    // GET (admin)
    Route::post('/',                    [PermintaanDaruratController::class, 'store']);    // POST (publik)
    Route::get('/resi/{nomor_resi}',    [PermintaanDaruratController::class, 'cekResi']); // GET cek resi
    Route::put('/{id}/status',          [PermintaanDaruratController::class, 'updateStatus']); // PUT (admin)
});

// ── FEEDBACK ─────────────────────────────────────────────────────────────
Route::prefix('feedback')->group(function () {
    Route::get('/',        [FeedbackController::class, 'index']);   // GET (admin)
    Route::post('/',       [FeedbackController::class, 'store']);   // POST (publik)
    Route::delete('/{id}', [FeedbackController::class, 'destroy']); // DELETE (admin)
});