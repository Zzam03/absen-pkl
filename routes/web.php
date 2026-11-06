<?php

use App\Http\Controllers\Admin\RekapController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    // Setelah login, arahkan sesuai role
    Route::get('/dashboard', function () {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.rekap')
            : redirect()->route('absen');
    })->name('dashboard');

    // ===== Peserta =====
    Route::get('/absen', [AttendanceController::class, 'index'])->name('absen');
    Route::post('/absen/masuk', [AttendanceController::class, 'masuk'])->name('absen.masuk');
    Route::post('/absen/pulang', [AttendanceController::class, 'pulang'])->name('absen.pulang');
    Route::get('/riwayat', [AttendanceController::class, 'riwayat'])->name('riwayat');

    Route::get('/izin', [IzinController::class, 'create'])->name('izin.create');
    Route::post('/izin', [IzinController::class, 'store'])->name('izin.store');

    // ===== Admin =====
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/rekap', [RekapController::class, 'index'])->name('rekap');
        Route::resource('peserta', PesertaController::class)->except('show');
    });

    // Bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';