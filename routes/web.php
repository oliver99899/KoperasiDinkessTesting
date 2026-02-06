<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\AngsuranController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VerifikatorController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\PembayaranAngsuranController;
use App\Http\Controllers\VerifikatorPembayaranAngsuranController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/aktivasi/{token}', [AuthController::class, 'showActivationForm'])->name('aktivasi.form');

Route::get('/cek-validasi/{hash}', [PinjamanController::class, 'verifikasiQr'])
    ->name('verifikasi.pinjaman')
    ->where('hash', '.*');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware(['auth', 'prevent.back'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/impersonate/leave', [AdminController::class, 'stopImpersonate'])->name('impersonate.leave');

    Route::post('/ui/mode', function (\Illuminate\Http\Request $request) {
        $request->validate(['mode' => 'required|in:anggota,admin,verifikator']);
        $user = $request->user();
        if ($request->mode === 'admin' && !$user->hasRole('admin')) abort(403);
        if ($request->mode === 'verifikator' && !$user->hasRole('verifikator')) abort(403);
        session(['sidebar_mode' => $request->mode]);
        return back();
    })->name('ui.mode');

    Route::get('/setup-profile', [ProfileController::class, 'showSetupForm'])->name('profile.setup');
    Route::post('/setup-profile', [ProfileController::class, 'storeSetup'])->name('profile.store');

    Route::middleware(['check.status'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/settings', [ProfileController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [ProfileController::class, 'update'])->name('settings.update');

        Route::get('/simpanan', [SimpananController::class, 'index'])->name('simpanan.index');
        Route::get('/pinjaman', [PinjamanController::class, 'index'])->name('pinjaman.index');
        Route::get('/pinjaman/create', [PinjamanController::class, 'create'])->name('pinjaman.create');
        Route::delete('/pinjaman/{id}', [PinjamanController::class, 'destroy'])->name('pinjaman.destroy');
        Route::post('/pinjaman', [PinjamanController::class, 'store'])->name('pinjaman.store');
        Route::get('/pinjaman/{id}/download-bukti', [PinjamanController::class, 'downloadBukti'])->name('pinjaman.download');
        Route::get('/angsuran', [AngsuranController::class, 'indexUser'])->name('angsuran.index');

        Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
            Route::get('/transfer', [PembayaranAngsuranController::class, 'index'])->name('transfer.index');
            Route::get('/transfer/{pinjaman_id}/{angsuran_ke}', [PembayaranAngsuranController::class, 'create'])->name('transfer.create');
            Route::post('/transfer/{pinjaman_id}/{angsuran_ke}', [PembayaranAngsuranController::class, 'store'])->name('transfer.store');
        });

        Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
            Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
            Route::get('/users', [AdminController::class, 'users'])->name('users.index');
            Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
            Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
            Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
            Route::post('/users/{id}/impersonate', [AdminController::class, 'impersonate'])->name('users.impersonate');
            Route::get('/unit-kerja', [UnitKerjaController::class, 'index'])->name('unit-kerja.index');
            Route::post('/unit-kerja', [UnitKerjaController::class, 'store'])->name('unit-kerja.store');
            Route::put('/unit-kerja/{id}', [UnitKerjaController::class, 'update'])->name('unit-kerja.update');
            Route::delete('/unit-kerja/{id}', [UnitKerjaController::class, 'destroy'])->name('unit-kerja.destroy');
        });

        Route::middleware(['role:verifikator'])->prefix('verifikator')->name('verifikator.')->group(function () {
            Route::get('/dashboard', [VerifikatorController::class, 'index'])->name('dashboard');
            Route::get('/laporan/tahunan', [VerifikatorController::class, 'downloadLaporanTahunan'])->name('laporan.tahunan');

            Route::get('/simpanan/members', [VerifikatorController::class, 'members'])->name('simpanan.members');
            Route::get('/simpanan', function() { return redirect()->route('verifikator.simpanan.members'); });
            Route::post('/simpanan', [VerifikatorController::class, 'storeSimpanan'])->name('simpanan.store');
            Route::get('/simpanan/{id}/history-data', [VerifikatorController::class, 'getHistoryData'])->name('simpanan.history.data');
            Route::delete('/simpanan/{id}', [VerifikatorController::class, 'destroySimpanan'])->name('simpanan.destroy');

            Route::get('/pinjaman', [VerifikatorController::class, 'indexPinjaman'])->name('pinjaman.index');
            Route::get('/pinjaman/{id}', [VerifikatorController::class, 'showPinjaman'])->name('pinjaman.show');
            Route::post('/pinjaman/{id}/approve', [VerifikatorController::class, 'approvePinjaman'])->name('pinjaman.approve');
            Route::post('/pinjaman/{id}/reject', [VerifikatorController::class, 'rejectPinjaman'])->name('pinjaman.reject');

            Route::get('/angsuran', [VerifikatorController::class, 'indexAngsuran'])->name('angsuran.index');
            Route::post('/angsuran', [VerifikatorController::class, 'storeAngsuran'])->name('angsuran.store');

            Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
                Route::get('/transfer', [VerifikatorPembayaranAngsuranController::class, 'index'])->name('transfer.index');
                Route::get('/transfer/{id}', [VerifikatorPembayaranAngsuranController::class, 'show'])->name('transfer.show');
                Route::post('/transfer/{id}/approve', [VerifikatorPembayaranAngsuranController::class, 'approve'])->name('transfer.approve');
                Route::post('/transfer/{id}/reject', [VerifikatorPembayaranAngsuranController::class, 'reject'])->name('transfer.reject');
            });
        });
    });
});