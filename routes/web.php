<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VerifikatorController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/aktivasi/{token}', [AuthController::class, 'showActivationForm'])->name('aktivasi.form');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::get('/cek-validasi/{hash}', [PinjamanController::class, 'verifikasiQr'])
    ->name('verifikasi.pinjaman')
    ->where('hash', '.*');

Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/setup-profile', [ProfileController::class, 'showSetupForm'])->name('profile.setup');
    Route::post('/setup-profile', [ProfileController::class, 'storeSetup'])->name('profile.store');

    Route::middleware(['check.profile'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/simpanan', [SimpananController::class, 'index'])->name('simpanan.index');

        Route::get('/pinjaman', [PinjamanController::class, 'index'])->name('pinjaman.index');
        Route::get('/pinjaman/create', [PinjamanController::class, 'create'])->name('pinjaman.create');
        Route::post('/pinjaman', [PinjamanController::class, 'store'])->name('pinjaman.store');
        Route::get('/pinjaman/{id}/download-bukti', [PinjamanController::class, 'downloadBukti'])->name('pinjaman.download');

        Route::get('/settings', [ProfileController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [ProfileController::class, 'update'])->name('settings.update');
    });

    Route::middleware(['is_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    });

    Route::middleware(['is_verifikator'])->prefix('verifikator')->name('verifikator.')->group(function () {
        Route::get('/dashboard', [VerifikatorController::class, 'index'])->name('dashboard');
        Route::get('/simpanan/create', [VerifikatorController::class, 'createSimpanan'])->name('simpanan.create');
        Route::post('/simpanan', [VerifikatorController::class, 'storeSimpanan'])->name('simpanan.store');
        Route::get('/pinjaman', [VerifikatorController::class, 'indexPinjaman'])->name('pinjaman.index');
        Route::get('/pinjaman/{id}', [VerifikatorController::class, 'showPinjaman'])->name('pinjaman.show');
        Route::post('/pinjaman/{id}/approve', [VerifikatorController::class, 'approvePinjaman'])->name('pinjaman.approve');
        Route::post('/pinjaman/{id}/reject', [VerifikatorController::class, 'rejectPinjaman'])->name('pinjaman.reject');
    });

});