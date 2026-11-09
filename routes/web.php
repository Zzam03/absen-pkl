<?php

use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PlacementController;
use App\Http\Controllers\Admin\RekapController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Pembimbing\ParafController;
use App\Http\Controllers\Pembimbing\PenilaianController;
use App\Http\Controllers\Pembimbing\SiswaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\AbsenController;
use App\Http\Controllers\Student\JurnalController;
use App\Http\Controllers\Student\NilaiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    // Setelah login, arahkan sesuai role
    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin'      => redirect()->route('admin.dashboard'),
            'pembimbing' => redirect()->route('pembimbing.dashboard'),
            default      => redirect()->route('student.dashboard'),
        };
    })->name('dashboard');

    // Sertifikat (admin, atau siswa pemilik; dicek di controller)
    Route::get('/sertifikat/{placement}', [CertificateController::class, 'show'])->name('sertifikat');

    // ===== SISWA =====
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'student'])->name('dashboard');

        Route::get('/absen', [AbsenController::class, 'index'])->name('absen');
        Route::post('/absen/masuk', [AbsenController::class, 'masuk'])->name('absen.masuk');
        Route::post('/absen/pulang', [AbsenController::class, 'pulang'])->name('absen.pulang');
        Route::get('/riwayat', [AbsenController::class, 'riwayat'])->name('riwayat');
        Route::get('/izin', [AbsenController::class, 'izinCreate'])->name('izin.create');
        Route::post('/izin', [AbsenController::class, 'izinStore'])->name('izin.store');

        Route::resource('jurnal', JurnalController::class)
            ->parameters(['jurnal' => 'journal'])
            ->only(['index', 'create', 'store', 'edit', 'update']);

        Route::get('/nilai', [NilaiController::class, 'show'])->name('nilai');
    });

    // ===== PEMBIMBING =====
    Route::middleware('role:pembimbing')->prefix('pembimbing')->name('pembimbing.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'pembimbing'])->name('dashboard');

        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/{placement}', [SiswaController::class, 'show'])->name('siswa.show');

        Route::post('/jurnal/{journal}/paraf', [ParafController::class, 'paraf'])->name('jurnal.paraf');
        Route::post('/jurnal/{journal}/revisi', [ParafController::class, 'revisi'])->name('jurnal.revisi');

        Route::get('/nilai/{placement}', [PenilaianController::class, 'form'])->name('nilai.form');
        Route::post('/nilai/{placement}', [PenilaianController::class, 'store'])->name('nilai.store');
    });

    // ===== ADMIN HR =====
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        Route::resource('users', UserController::class)->except('show');
        Route::resource('locations', LocationController::class)->except('show');
        Route::resource('placements', PlacementController::class)->except('show');

        Route::get('/rekap', [RekapController::class, 'index'])->name('rekap');
        Route::get('/rekap/export/{format}', [RekapController::class, 'export'])
            ->whereIn('format', ['xlsx', 'pdf'])->name('rekap.export');
        Route::get('/jurnal', [RekapController::class, 'jurnal'])->name('jurnal');
    });

    // Bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';