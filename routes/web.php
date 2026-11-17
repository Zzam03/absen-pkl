<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Student\AttendanceController;
use App\Http\Controllers\JournalController;

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

    Route::middleware(['role:student'])->group(function () {
        Route::get('/siswa/absen', function () { return view('student.attendance'); });
        Route::post('/api/attendance', [AttendanceController::class, 'store']);
        Route::get('/siswa/jurnal', [JournalController::class, 'indexStudent'])->name('jurnal.index');
        Route::post('/siswa/jurnal', [JournalController::class, 'store'])->name('jurnal.store');
    });

    Route::middleware(['role:pembimbing_kantor,pembimbing_sekolah'])->group(function () {
        Route::get('/pembimbing/jurnal', [JournalController::class, 'indexPembimbing'])->name('pembimbing.jurnal');
        Route::put('/pembimbing/jurnal/{journal}', [JournalController::class, 'updateStatus'])->name('pembimbing.jurnal.update');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::post('/admin/schedule/generate', [ScheduleController::class, 'generateMonthly']);
    });

    Route::get('/profil', function () { return view('profile'); })->name('profile');
});