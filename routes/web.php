<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\WebAuthController; // Pastikan menggunakan namespace yang sesuai
use App\Http\Controllers\Web\WebLevelController; // Pastikan menggunakan namespace yang sesuai
use App\Http\Controllers\Web\WebMataPelajaranController; // Pastikan menggunakan namespace yang sesuai
use App\Http\Controllers\Web\WebSoalController; // Pastikan menggunakan namespace yang sesuai
use App\Http\Controllers\Web\WebHasilPembelajaranController; // Pastikan menggunakan namespace yang sesuai
// use App\Http\Controllers\Web\JawabanPenggunaController; // Pastikan menggunakan namespace yang sesuai
 
// use App\Http\Controllers\Web\WebAuthController;


Route::get('/', [WebAuthController::class, 'showLoginForm'])->name('login'); // Menampilkan halaman login pertama kali
Route::post('/', [WebAuthController::class, 'login']); // Menangani form login
Route::post('/register', [WebAuthController::class, 'register']);

// Routes yang memerlukan otentikasi

Route::middleware(['auth:sanctum', 'role:admin,super_admin'])->group(function () {
    Route::get('/home', [WebAuthController::class, 'home'])->name('home'); // Halaman setelah login
// Route untuk logout


Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// Menampilkan halaman manajemen level
Route::get('/admin/levels', [WebLevelController::class, 'index'])->name('admin.levels.index');

    // Menambahkan level baru
    Route::post('/admin/levels', [WebLevelController::class, 'store'])->name('admin.levels.store');

    Route::get('/admin/levels/filter/{id_mataPelajaran}', [WebLevelController::class, 'filter'])->name('admin.levels.filter');


    // Mengupdate level
    Route::put('/admin/levels/{id}', [WebLevelController::class, 'update'])->name('admin.levels.update');

    // Menghapus level
    Route::delete('/admin/levels/{id}', [WebLevelController::class, 'destroy'])->name('admin.levels.destroy');

    //  Route::get('/admin/levels/mata-pelajaran/{id}', [WebLevelController::class, 'getLevelsByMataPelajaran']);
    Route::get('/admin/levels/mata-pelajaran/{id_mataPelajaran}', [WebLevelController::class, 'getLevelsByMataPelajaran']);


    Route::get('matapelajaran', [WebMataPelajaranController::class, 'index'])->name('admin.matapelajaran.index');
    Route::post('matapelajaran', [WebMataPelajaranController::class, 'store'])->name('admin.matapelajaran.store');
    Route::put('matapelajaran/{id}', [WebMataPelajaranController::class, 'update'])->name('admin.matapelajaran.update');
    Route::delete('matapelajaran/{id}', [WebMataPelajaranController::class, 'destroy'])->name('admin.matapelajaran.destroy');


    ///soal

    Route::get('/soal', [WebSoalController::class, 'index'])->name('admin.soal.index');
    Route::post('/soal', [WebSoalController::class, 'store'])->name('soal.store');
    Route::delete('/soal/{id}', [WebSoalController::class, 'destroy'])->name('soal.destroy');
    // Route::get('/admin/soal/{id}', [WebSoalController::class, 'show'])->name('admin.soal.show');
    // Route::get('/admin/soal/{id}', [WebSoalController::class, 'show'])->name('admin.soal.show');

    // Route::get('/admin/matapelajaran/{id}', [WebSoalController::class, 'show'])->name('admin.matapelajaran.show_soal');


    Route::get('/matapelajaran/{id}/levels', [WebSoalController::class, 'showLevels'])->name('admin.matapelajaran.show_levels');
    Route::put('/admin/matapelajaran/{id}', [WebMataPelajaranController::class, 'update'])->name('admin.matapelajaran.update');

    Route::get('/level/{id}/soal', [WebSoalController::class, 'showSoal'])->name('admin.level.show_soal');

    // Route::get('/soal/create/{id_level}', [WebSoalController::class, 'create'])->name('soal.create');

    Route::get('/soal/create/{id_level}', [WebSoalController::class, 'create'])->name('soal.create');
    Route::post('/soal/store', [WebSoalController::class, 'store'])->name('soal.store');
    Route::get('/soal/{id}', [WebSoalController::class, 'showSoal'])->name('soal.list');

Route::get('/soal/{id}/list', [WebSoalController::class, 'showSoal'])->name('soal.list');

Route::get('/soal/{id}/edit', [WebSoalController::class, 'edit'])->name('soal.edit');
Route::put('/soal/{id}', [WebSoalController::class, 'update'])->name('soal.update');


// Hasil Pembelajaran
Route::get('/admin/hasilpembelajaran', [WebHasilPembelajaranController::class, 'index'])->name('admin.hasilpembelajaran.index');

Route::get('/admin/hasilpembelajaran/{id}', [WebHasilPembelajaranController::class, 'show'])->name('admin.hasilpembelajaran.show');
Route::get('/admin/hasilpembelajaran/{id}', [WebHasilPembelajaranController::class, 'show'])->name('admin.hasilpembelajaran.show');

//logout

Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');


});


Route::middleware(['auth:sanctum', 'role:super_admin'])->group(function () {
    Route::get('/admin/register', [WebAuthController::class, 'showRegistrationForm'])->name('super_admin.registration_admin');
    Route::post('/admin/register', [WebAuthController::class, 'registerAdmin'])->name('admin.register');

});