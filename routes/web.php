<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProgramPendidikanController;
use App\Http\Controllers\TeknologiController;
use App\Http\Controllers\SarprasController;
use App\Http\Controllers\SdmController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CetakLaporanController;

// Redirect root to dashboard or login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',           [DashboardController::class,       'index'])->name('dashboard');
    Route::resource('sekolah',         SekolahController::class);
    Route::resource('users',           UserController::class);
    Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    Route::get('/api/kota-kabupaten',  [SekolahController::class, 'kotaByProvinsi'])->name('api.kota');
    Route::get('/api/kecamatan',       [SekolahController::class, 'kecamatanByKota'])->name('api.kecamatan');
    Route::get('/api/kelurahan',       [SekolahController::class, 'kelurahanByKecamatan'])->name('api.kelurahan');
    Route::get('/program-pendidikan',  [ProgramPendidikanController::class, 'index'])->name('program.index');
    Route::get('/teknologi',           [TeknologiController::class,        'index'])->name('teknologi.index');
    Route::get('/sarana-prasarana',    [SarprasController::class,          'index'])->name('sarpras.index');
    Route::get('/sdm',                 [SdmController::class,              'index'])->name('sdm.index');
    Route::get('/rekap-analisis',      [RekapController::class,            'index'])->name('rekap.index');
    Route::get('/cetak-laporan',        [CetakLaporanController::class,     'index'])->name('cetak-laporan.index');

    // Profile
    Route::get('/profile',             [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile',             [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar',     [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar',   [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
});
