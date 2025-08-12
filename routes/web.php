<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\GolonganController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\BonController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - accessible by both manager and admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Absensi - accessible by both manager and admin
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/{date}', [AbsensiController::class, 'show'])->name('absensi.show');
    
    // Karyawan routes
    Route::resource('karyawan', KaryawanController::class);
    
    // Golongan routes
    Route::resource('golongan', GolonganController::class);
    
    // Lokasi routes - only manager can create/edit/delete
    Route::resource('lokasi', LokasiController::class);
    
    // Bon routes
    Route::resource('bon', BonController::class);
    Route::post('/bon/{bon}/bayar-cicilan', [BonController::class, 'bayarCicilan'])->name('bon.bayar-cicilan');
    
    // Laporan routes - accessible by both manager and admin
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/keterlambatan/{karyawan}', [LaporanController::class, 'detailKeterlambatan'])->name('laporan.detail-keterlambatan');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export')->middleware('role:manager');
});

// API routes for fingerprint SDK
Route::post('/api/absensi', [AbsensiController::class, 'store'])->name('api.absensi.store');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
