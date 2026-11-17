<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Student\AttendanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Route Dashboard & API
    Route::get('/api/dashboard/chart-data', [DashboardController::class, 'getAttendanceChartData'])->name('api.chart');
    Route::get('/api/dashboard/summary', [DashboardController::class, 'getSummaryStats'])->name('api.summary');
    
    // Route Absensi Siswa
    Route::post('/api/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    
    // Route Admin Jadwal
    Route::post('/admin/schedule/generate', [ScheduleController::class, 'generateMonthly'])->name('admin.schedule.generate');
});

// ... (Biarkan route lain yang sudah ada di bawah sini) ...