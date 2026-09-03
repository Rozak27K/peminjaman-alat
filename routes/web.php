<?php

use App\Http\Controllers\AlatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriAlatController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin')->group(function (): void {
        Route::resource('users', UserController::class);
        Route::resource('kategori-alat', KategoriAlatController::class);
        Route::resource('alat', AlatController::class);
        Route::resource('peminjaman', PeminjamanController::class)->only(['edit', 'update', 'destroy']);
        Route::resource('pengembalian', PengembalianController::class)->only(['edit', 'update', 'destroy']);
        Route::resource('log-aktivitas', LogAktivitasController::class)->only('index');
    });

    Route::middleware('role:peminjam')->group(function (): void {
        Route::resource('peminjaman', PeminjamanController::class)->only(['create', 'store']);
    });

    Route::middleware('role:admin,petugas,peminjam')->group(function (): void {
        Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'show']);
        Route::resource('pengembalian', PengembalianController::class)->only(['index', 'create', 'store', 'show']);
    });

    Route::middleware('role:petugas')->group(function (): void {
        Route::patch('/peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::patch('/peminjaman/{peminjaman}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    });
});
