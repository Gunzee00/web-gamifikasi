<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WebAuthController;
use App\Http\Controllers\Web\WebLevelController;
use App\Http\Controllers\Web\WebMataPelajaranController;
use App\Http\Controllers\Web\WebSuperAdminAuthController;
use App\Http\Controllers\Web\WebSoalController;
use App\Http\Controllers\Web\WebHasilPembelajaranController;

// ============================
// Auth & Register
// ============================

// Login page
Route::get('/', [WebAuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [WebAuthController::class, 'login']);

// Register (user)
Route::post('/register', [WebAuthController::class, 'register']);

// ============================
// Route for Super Admin
// ============================

Route::middleware(['auth:sanctum', 'role:super_admin'])->prefix('admin')->group(function () {
    // Form dan proses registrasi admin
    Route::get('/register', [WebSuperAdminAuthController::class, 'showRegistrationForm'])->name('super_admin.registration_admin');
    Route::post('/register', [WebSuperAdminAuthController::class, 'registerAdmin'])->name('admin.register');

    // Manajemen akun user dan admin
    Route::get('/super-admin/manajemen-akun', [WebSuperAdminAuthController::class, 'manajemenAkun'])->name('super_admin.manajemen_akun');

    Route::get('/users', [WebSuperAdminAuthController::class, 'listUsers'])->name('super_admin.list_users');
    Route::get('/users/{id}/edit', [WebSuperAdminAuthController::class, 'editUser'])->name('super_admin.edit_user');
    Route::put('/users/{id}', [WebSuperAdminAuthController::class, 'updateUser'])->name('super_admin.update_user');
    Route::delete('/users/{id}', [WebSuperAdminAuthController::class, 'deleteUser'])->name('super_admin.delete_user');
    Route::get('/super-admin/akun/{id}/edit', [WebSuperAdminAuthController::class, 'editUser'])->name('super_admin.edit_user');
Route::put('/super-admin/akun/{id}', [WebSuperAdminAuthController::class, 'updateUser'])->name('super_admin.update_user');
Route::get('/super-admin/users', [WebSuperAdminAuthController::class, 'listUsers'])->name('super_admin.list_users');

});


// ============================
// Route for Admin & Super Admin
// ============================
Route::middleware(['auth:sanctum', 'role:admin,super_admin'])->group(function () {

    Route::get('/home', [WebAuthController::class, 'home'])->name('home');

    // Logout
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

    // ============================
    // Level Management
    // ============================
    Route::get('/admin/levels', [WebLevelController::class, 'index'])->name('admin.levels.index');
    Route::post('/admin/levels', [WebLevelController::class, 'store'])->name('admin.levels.store');
    Route::put('/admin/levels/{id}', [WebLevelController::class, 'update'])->name('admin.levels.update');
    Route::delete('/admin/levels/{id}', [WebLevelController::class, 'destroy'])->name('admin.levels.destroy');

    Route::get('/admin/levels/filter/{id_mataPelajaran}', [WebLevelController::class, 'filter'])->name('admin.levels.filter');
    Route::get('/admin/levels/mata-pelajaran/{id_mataPelajaran}', [WebLevelController::class, 'getLevelsByMataPelajaran']);

    // ============================
    // Mata Pelajaran
    // ============================
    Route::get('/matapelajaran', [WebMataPelajaranController::class, 'index'])->name('admin.matapelajaran.index');
    Route::post('/matapelajaran', [WebMataPelajaranController::class, 'store'])->name('admin.matapelajaran.store');
    Route::put('/matapelajaran/{id}', [WebMataPelajaranController::class, 'update'])->name('admin.matapelajaran.update');
    Route::delete('/matapelajaran/{id}', [WebMataPelajaranController::class, 'destroy'])->name('admin.matapelajaran.destroy');

    // ============================
    // Soal Management
    // ============================
    Route::get('/soal', [WebSoalController::class, 'index'])->name('admin.soal.index');
    Route::get('/soal/create/{id_level}', [WebSoalController::class, 'create'])->name('soal.create');
    Route::post('/soal/store', [WebSoalController::class, 'store'])->name('soal.store');
    Route::delete('/soal/{id}', [WebSoalController::class, 'destroy'])->name('soal.destroy');
    Route::get('soal/{id}/edit', [WebSoalController::class, 'edit'])->name('soal.edit');
     Route::put('/soal/{id}', [WebSoalController::class, 'update'])->name('soal.update');

     Route::get('/soal/{id_level}', [WebSoalController::class, 'index'])->name('soal.index');


    // Untuk melihat soal berdasarkan level
    Route::get('/level/{id}/soal', [WebSoalController::class, 'showSoal'])->name('admin.level.show_soal');

    // Untuk melihat level berdasarkan mata pelajaran   
    Route::get('/matapelajaran/{id}/levels', [WebSoalController::class, 'showLevels'])->name('admin.matapelajaran.show_levels');

    // ============================
    // Hasil Pembelajaran
    // ============================
    Route::get('/admin/hasilpembelajaran', [WebHasilPembelajaranController::class, 'index'])->name('admin.hasilpembelajaran.index');
    Route::get('/admin/hasilpembelajaran/{id}', [WebHasilPembelajaranController::class, 'show'])->name('admin.hasilpembelajaran.show');
    
});
