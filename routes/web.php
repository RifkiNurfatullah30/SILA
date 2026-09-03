<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HealthRecordController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\LansiaController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin routes
    Route::middleware('can:admin')->group(function () {
        Route::resource('lansia', LansiaController::class)->parameters(['lansia' => 'lansia']);
        Route::resource('kegiatan', KegiatanController::class);
        Route::resource('health-records', HealthRecordController::class);

        Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
        Route::post('/kehadiran', [KehadiranController::class, 'store'])->name('kehadiran.store');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
        Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel');
    });

    // Lansia portal
    Route::get('/portal-lansia', [\App\Http\Controllers\PortalLansiaController::class, 'index'])->name('portal.lansia');
});
