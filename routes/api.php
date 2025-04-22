<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MataPelajaranController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\SoalController;
use App\Http\Controllers\Api\JawabanPenggunaController;

// Cek Status API
Route::get('/status', function () {
    return response()->json([
        'code' => 200,
        'message' => 'API is running successfully',
    ], 200);
});

// Auth - Public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/lupa-password', [AuthController::class, 'lupaPassword']);

// Auth - Protected
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);
    Route::get('/', [AuthController::class, 'x']);
});

// Routes untuk Admin & Super Admin
Route::middleware(['auth:sanctum', 'role:admin,super_admin'])->prefix('admin')->group(function () {

    // Mata Pelajaran
    Route::post('/matapelajaran', [MataPelajaranController::class, 'store']);
    Route::get('/matapelajaran/{id}', [MataPelajaranController::class, 'show']);
    Route::put('/matapelajaran/{id}', [MataPelajaranController::class, 'update']);
    Route::delete('/matapelajaran/{id}', [MataPelajaranController::class, 'destroy']);

    // Level
    Route::post('/levels', [LevelController::class, 'store']);
    Route::put('/levels/{id}', [LevelController::class, 'update']);
    Route::delete('/levels/{id}', [LevelController::class, 'destroy']);

    // Soal
    Route::get('/soal', [SoalController::class, 'index']);
    Route::get('/soal/level/{id_level}', [SoalController::class, 'getByLevel']);
    Route::post('/soal', [SoalController::class, 'store']);
    Route::get('/soal/{id}', [SoalController::class, 'show']);
    Route::put('/soal/{id}', [SoalController::class, 'update']);
    Route::delete('/soal/{id}', [SoalController::class, 'destroy']);
});

// Routes untuk User
Route::middleware(['auth:sanctum', 'role:user'])->prefix('user')->group(function () {

    // Mata Pelajaran & Level
    Route::get('/matapelajaran', [MataPelajaranController::class, 'index']);
    Route::get('/levels', [LevelController::class, 'index']);
    Route::get('/matapelajaran/{id_mataPelajaran}/levels', [LevelController::class, 'getLevelsByMataPelajaran']);

    // Soal
    Route::get('/soal', [SoalController::class, 'index']);
    Route::get('/soal/level/{id_level}', [SoalController::class, 'getByLevel']);
    Route::get('/soal/matapelajaran/{id_mataPelajaran}/level/{id_level}', [SoalController::class, 'getByMataPelajaranAndLevel']);

    // Jawaban & Skor
    Route::post('/jawaban', [JawabanPenggunaController::class, 'simpanJawaban']);
    Route::post('/cek-kelulusan-level', [JawabanPenggunaController::class, 'cekKelulusanLevel']);
    Route::get('/skor-akhir', [JawabanPenggunaController::class, 'getSkorAkhir']);
    Route::get('/skor-akhir-level', [JawabanPenggunaController::class, 'getSkorAkhirPerLevel']);
});
