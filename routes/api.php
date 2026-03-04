<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PerizinanController;
use App\Http\Controllers\Api\KehadiranController;
use App\Http\Controllers\Api\WajahController;



// =======================
// PUBLIC ROUTES
// =======================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::post('/request-otp', [AuthController::class, 'requestOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPasswordWithOtp']);

// =======================
// PROTECTED ROUTES
// =======================
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // =======================
    // PERIZINAN
    // =======================
    Route::post('/perizinan', [PerizinanController::class, 'store']);
    Route::get('/perizinan', [PerizinanController::class, 'index']);

    // =======================
    // KEHADIRAN
    // =======================
    Route::post('/absen/masuk', [KehadiranController::class, 'absenMasuk']);
    Route::get('/kehadiran', [KehadiranController::class, 'index']);

    // ⭐ GENERATE KEHADIRAN
    Route::post('/generate-kehadiran', [KehadiranController::class, 'generate']);

    // =======================
    // WAJAH
    // =======================
    Route::post('/wajah', [WajahController::class, 'store']);
    Route::get('/wajah/check', [WajahController::class, 'check']);

});
