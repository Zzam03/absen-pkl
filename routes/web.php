<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Student\AttendanceController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PlacementController;
use App\Http\Controllers\Siswa\SiswaController;
use App\Http\Controllers\Pembimbing\PembimbingController;

Route::get('/', function () { return redirect('/login'); });
require __DIR__.'/auth.php'; 

Route::middleware(['auth', 'devicelock'])->group(function () {
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        if ($role === 'admin') return view('admin.dashboard');
        if (in_array($role, ['pembimbing_kantor', 'pembimbing_sekolah'])) return view('pembimbing.dashboard');
        return view('siswa.dashboard');
    })->name('dashboard');

    Route::get('/api/dashboard/chart-data', [DashboardController::class, 'getAttendanceChartData']);
    Route::get('/api/dashboard/summary', [DashboardController::class, 'getSummaryStats']);

    // Profil (Semua Role)
    Route::get('/profil', function () { return view('profile'); })->name('profile');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');

    // === SISWA ===
    Route::middleware(['role:student'])->group(function () {
        Route::get('/siswa/absen', function () { return view('student.attendance'); });
        Route::post('/api/attendance', [AttendanceController::class, 'store']);
        Route::get('/siswa/jurnal', [JournalController::class, 'indexStudent'])->name('jurnal.index');
        Route::post('/siswa/jurnal', [JournalController::class, 'store'])->name('jurnal.store');
        Route::get('/siswa/riwayat', [SiswaController::class, 'riwayat'])->name('siswa.riwayat');
        Route::get('/siswa/jadwal', [SiswaController::class, 'jadwal'])->name('siswa.jadwal');
    });

    // === PEMBIMBING ===
    Route::middleware(['role:pembimbing_kantor,pembimbing_sekolah'])->group(function () {
        Route::get('/pembimbing/siswa', [PembimbingController::class, 'siswa'])->name('pembimbing.siswa');
        Route::get('/pembimbing/jurnal', [JournalController::class, 'indexPembimbing'])->name('pembimbing.jurnal');
        Route::put('/pembimbing/jurnal/{journal}', [JournalController::class, 'updateStatus'])->name('pembimbing.jurnal.update');
        Route::get('/pembimbing/laporan', [PembimbingController::class, 'laporan'])->name('pembimbing.laporan');
    });

    // === ADMIN ===
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/admin/schedule/generate', [ScheduleController::class, 'generateMonthly']);
        Route::resource('/admin/users', UserController::class);
        Route::resource('/admin/locations', LocationController::class);
        Route::resource('/admin/placements', PlacementController::class);
    });
});