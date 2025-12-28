<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Siswa\PeminjamanController;
use App\Http\Controllers\BebasPerpusController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==================================================================
// ========================== SEMUA ROUTE AUTH ======================
// ==================================================================
Route::middleware('auth')->group(function () {

    // ====================== SISWA ======================
    Route::middleware('role:siswa')->group(function () {

        // Dashboard Siswa
        Route::get('/siswa/dashboard', [SiswaDashboardController::class, 'index'])->name('siswa.dashboard');
        Route::post('/siswa/dashboard/update', [SiswaDashboardController::class, 'updateProfil'])
            ->name('siswa.dashboard.update');

        // Peminjaman
        Route::get('/siswa/peminjaman', [PeminjamanController::class, 'index'])
            ->name('siswa.peminjaman.index');
        Route::get('/siswa/peminjaman/create', [PeminjamanController::class, 'create'])
            ->name('siswa.peminjaman.create');
        Route::post('/siswa/peminjaman', [PeminjamanController::class, 'store'])
            ->name('siswa.peminjaman.store');
        Route::post('/siswa/peminjaman/{id}/ajukan-kembali', [PeminjamanController::class, 'ajukanKembali'])
            ->name('siswa.peminjaman.ajukan-kembali');
        Route::get('/siswa/pengembalian', [PeminjamanController::class, 'pengembalian'])
            ->name('siswa.pengembalian.index');

        // BEBAS PERPUS - SISWA
        Route::get('/bebas-perpus', [BebasPerpusController::class, 'index'])
            ->name('bebas-perpus.index');
        Route::post('/bebas-perpus/ajukan', [BebasPerpusController::class, 'ajukan'])
            ->name('bebas-perpus.ajukan');
    });

    // ====================== ADMIN ======================
  // ====================== ADMIN ======================
  Route::prefix('admin')->middleware('role:admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Kelola Siswa
    Route::get('siswa', [SiswaController::class, 'index'])->name('admin.siswa.index');
    Route::get('siswa/create', [SiswaController::class, 'create'])->name('admin.siswa.create');
    Route::post('siswa', [SiswaController::class, 'store'])->name('admin.siswa.store');
    Route::get('siswa/{id}/edit', [SiswaController::class, 'edit'])->name('admin.siswa.edit');
    Route::put('siswa/{id}', [SiswaController::class, 'update'])->name('admin.siswa.update');
    Route::delete('siswa/{id}', [SiswaController::class, 'destroy'])->name('admin.siswa.destroy');
    Route::get('siswa/download-template', [SiswaController::class, 'downloadTemplate'])
        ->name('admin.siswa.download-template');

    // Kelola Buku — PAKAI resource() tanpa name() di group
    Route::get('buku/download-template', [BukuController::class, 'downloadTemplate'])
        ->name('admin.buku.download-template');
    Route::resource('buku', BukuController::class)->except(['show']);
    // → Otomatis buat: buku.index, buku.create, dll

    // Validasi Peminjaman
    Route::get('peminjaman', [PeminjamanController::class, 'index'])
        ->name('admin.peminjaman.index');
    Route::post('peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])
        ->name('peminjaman.approve');
    Route::post('peminjaman/{id}/terima-kembali', [PeminjamanController::class, 'terimaKembali'])
        ->name('peminjaman.terima-kembali');
    Route::post('peminjaman/{id}/tolak', [PeminjamanController::class, 'tolak'])
        ->name('peminjaman.tolak');

    // Bebas Perpus Admin
    Route::get('/bebas-perpus', [BebasPerpusController::class, 'adminIndex'])
        ->name('admin.bebas-perpus.index');
    Route::post('/bebas-perpus/{id}/proses', [BebasPerpusController::class, 'proses'])
        ->name('bebas-perpus.proses');
    Route::get('/bebas-perpus/{id}/cetak', [BebasPerpusController::class, 'cetak'])
        ->name('bebas-perpus.cetak');
});
});
// Tambahkan di luar group atau di dalam group auth (bebas)
Route::get('/bebas-perpus/verify/{id}', [BebasPerpusController::class, 'verify'])
    ->name('bebas-perpus.verify');
