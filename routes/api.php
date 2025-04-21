<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\MataPelajaranController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\SoalController;
use App\Http\Controllers\Api\VisualController;
use App\Http\Controllers\Api\JawabanPenggunaController;


//daftar user
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/lupa-password', [AuthController::class, 'lupaPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/', [AuthController::class, 'x']);


});

Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'me']);


Route::middleware(['auth:sanctum', 'role:admin,super_admin'])->group(function () {
  
    Route::post('/matapelajaran', [MataPelajaranController::class, 'store']);
    Route::get('/matapelajaran/{id}', [MataPelajaranController::class, 'show']);
    Route::put('/matapelajaran/{id}', [MataPelajaranController::class, 'update']);
    Route::delete('/matapelajaran/{id}', [MataPelajaranController::class, 'destroy']);
});

//level
Route::middleware(['auth:sanctum', 'role:admin,super_admin'])->group(function () {
    // Route::get('/levels', [LevelController::class, 'index']);
    Route::post('/levels', [LevelController::class, 'store']);
    Route::delete('/levels/{id}', [LevelController::class, 'destroy']);
    Route::put('/levels/{id}', [LevelController::class, 'update']); 

    //soal
    Route::get('/soal', [SoalController::class, 'index']); // Semua soal
    Route::get('/soal/level/{id_level}', [SoalController::class, 'getByLevel']); // Soal berdasarkan level

    Route::post('/soal', [SoalController::class, 'store']); // Menambah soal baru
    Route::get('/soal/{id}', [SoalController::class, 'show']); // Menampilkan soal berdasarkan ID
    Route::put('/soal/{id}', [SoalController::class, 'update']); // Mengupdate soal
    Route::delete('/soal/{id}', [SoalController::class, 'destroy']); // Menghapus soal

    //menjawab soal kinestetik

    
});


//get di mobile user
Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
    Route::get('/matapelajaran', [MataPelajaranController::class, 'index']);
   Route::get('/soal', [SoalController::class, 'index']); // Semua soal
    // Route::get('/soal/level/{id_level}', [SoalController::class, 'getByLevel']); // Soal berdasarkan level
    //soal berdasarkan mapel dan level
    Route::get('/soal/matapelajaran/{id_mataPelajaran}/level/{id_level}', [SoalController::class, 'getByMataPelajaranAndLevel']);
Route::post('/jawaban', [JawabanPenggunaController::class, 'simpanJawaban']);
         
         Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
            Route::get('/matapelajaran', [MataPelajaranController::class, 'index']);
            Route::get('/levels', [LevelController::class, 'index']);
            Route::get('/soal/level/{id_level}', [SoalController::class, 'getByLevel']); // Soal berdasarkan level
             Route::get('/matapelajaran/{id_mataPelajaran}/levels', [LevelController::class, 'getLevelsByMataPelajaran']);
             //cek kelulusan
             Route::post('/cek-kelulusan-level', [JawabanPenggunaController::class, 'cekKelulusanLevel']);
            
             //skor setelah mengerjakan soal
             Route::get('/skor-akhir', [JawabanPenggunaController::class, 'getSkorAkhir']);


            //get skor level per level
             Route::get('/skor-akhir-level', [JawabanPenggunaController::class, 'getSkorAkhirPerLevel']);


        });

}); 
 