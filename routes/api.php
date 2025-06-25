
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\MataPelajaranController;
use App\Http\Controllers\Api\RankPenggunaController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\SoalController;
 
use App\Http\Controllers\Api\VisualController;
use App\Http\Controllers\Api\JawabanPenggunaController;
use App\Http\Controllers\Api\TopikController;

// get status
Route::get('/status', function () {
    return response()->json([
        'code' => 200,
        'message' => 'api is running successfully',
    ], 200);
});

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


Route::middleware(['auth:sanctum', 'role:admin,super_admin'])->group(function () {
    //level
    Route::post('/levels', [LevelController::class, 'store']);
    Route::get('/levels-admin', [LevelController::class, 'index']);
    Route::get('/levels/{id}', [LevelController::class, 'show']);
    Route::delete('/levels/{id}', [LevelController::class, 'destroy']);
    Route::put('/levels/{id}', [LevelController::class, 'update']); 

    //soal
    Route::get('/soal', [SoalController::class, 'index']); // Semua soal
    Route::get('/soal/level/{id_level}', [SoalController::class, 'getByLevel']); // Soal berdasarkan level
    Route::post('/soal', [SoalController::class, 'store']); // Menambah soal baru
    Route::get('/soal/{id}', [SoalController::class, 'show']); // Menampilkan soal berdasarkan ID
    Route::put('/soal/{id}', [SoalController::class, 'update']); // Mengupdate soal
    Route::delete('/soal/{id}', [SoalController::class, 'destroy']); // Menghapus soal
    
    //topik

 
});

Route::middleware(['auth:sanctum', 'role:admin,super_admin'])->group(function () {
 
    //topik
 

    Route::post('/topik', [TopikController::class, 'store']); 
    Route::put('/topik/{id}', [TopikController::class, 'update']); 
    Route::delete('/topik/{id}', [TopikController::class, 'destroy']); 
    Route::get('/level/{id}/topik', [TopikController::class, 'getTopikByLevel']);    

});

//admin dan user bisa akses

Route::middleware(['auth:sanctum', 'role:user,admin, super_admin'])->group(function () {

   Route::get('/topik', [TopikController::class, 'index']); 
    Route::get('/topik/{id}', [TopikController::class, 'show']); 
    //get by id level
    Route::get('/topik/level/{id_level}', [TopikController::class, 'getByLevel']);
    Route::get('/level/{id}/topik', [TopikController::class, 'getTopikByLevel']);
    Route::get('/soal/topik/{id_topik}', [SoalController::class, 'getByTopik']);
    Route::get('/rank-saya', [RankPenggunaController::class, 'getRank']);



});


//get di mobile user
Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
    Route::get('/matapelajaran', [MataPelajaranController::class, 'index']);
   Route::get('/soal', [SoalController::class, 'index']); // Semua soal

   //topik user juga
    //    Route::get('/topik', [TopikController::class, 'index']); 
    // Route::get('/topik/level/{id_level}', [TopikController::class, 'getByLevel']);
    // Route::get('/topik/{id}', [TopikController::class, 'show']); 


    //soal berdasarkan mapel dan level
    Route::get('/soal/matapelajaran/{id_mataPelajaran}/level/{id_level}', [SoalController::class, 'getByMataPelajaranAndLevel']);
    //menjawab soal
Route::post('/jawaban', [JawabanPenggunaController::class, 'simpanJawaban']);
//cek skor pengguna
Route::get('/skor', [JawabanPenggunaController::class, 'getSkorSaya']);
// Route::get('/bintang-saya/{id_level}', [JawabanPenggunaController::class, 'getBintangSayaByLevel']);
Route::get('/bintang-saya/{id_topik}', [JawabanPenggunaController::class, 'getBintangSayaByTopik']);

         Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
            Route::get('/matapelajaran', [MataPelajaranController::class, 'index']);
            Route::get('/levels', [LevelController::class, 'index']);
            Route::get('/soal/level/{id_level}', [SoalController::class, 'getByLevel']); // Soal berdasarkan level
             Route::get('/matapelajaran/{id_mataPelajaran}/levels', [LevelController::class, 'getLevelsByMataPelajaran']);
             //cek kelulusan
             Route::post('/cek-kelulusan-topik', [JawabanPenggunaController::class, 'cekKelulusanTopik']);
            //  skor terbaru
            Route::middleware('auth:api')->get('/skor-terbaru', [JawabanPenggunaController::class, 'getSkorTerbaru']);
             //skor setelah mengerjakan soal
             Route::get('/skor-akhir', [JawabanPenggunaController::class, 'getSkorAkhir']);

            //get skor level per level
             Route::get('/skor-akhir-level', [JawabanPenggunaController::class, 'getSkorAkhirPerLevel']);
             //Route::middleware('auth:sanctum')->get('/jumlah-benar-level-terbaru', [JawabanPenggunaController::class, 'getJumlahBenarLevelTerbaru']);
             Route::get('/jumlah-benar-level-terbaru', [JawabanPenggunaController::class, 'getJumlahBenarLevelTerbaru']);            
        });

       

}); 