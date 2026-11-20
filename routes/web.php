
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PlacementController;
use App\Http\Controllers\Admin\RekapController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Student\AbsenController;
use App\Http\Controllers\Student\JurnalController as StudentJurnalController;
use App\Http\Controllers\Student\NilaiController;
use App\Http\Controllers\Pembimbing\SiswaController;
use App\Http\Controllers\Pembimbing\JadwalController as JadwalPembimbingController;
use App\Http\Controllers\Pembimbing\JadwalSekolahController;
use App\Http\Controllers\Pembimbing\ParafController;
use App\Http\Controllers\Pembimbing\PenilaianController;
use App\Http\Controllers\Pembimbing\RekapSekolahController;
use App\Http\Controllers\Pembimbing\RekapKantorController;
use App\Models\Placement;
use App\Models\Attendance;
use App\Models\Journal;
use App\Models\Schedule;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'devicelock'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $role = $user->role;

        if ($role === 'admin') {
            return view('admin.dashboard');
        }

        // DASHBOARD PEMBIMBING
        if (in_array($role, ['pembimbing_kantor', 'pembimbing_sekolah'])) {

            $placements = Placement::with('student')
                ->where('status', 'active')
                ->whereHas('student', function ($query) {
                    $query->where('role', 'student');
                });

            if ($role === 'pembimbing_kantor') {
                $placements->where('pembimbing_id', $user->id);
            }

            if ($role === 'pembimbing_sekolah') {
                $placements->whereHas('student', function ($query) use ($user) {
                    $query->where('role', 'student')
                        ->where('school_name', $user->school_name);
                });
            }

            $placementIds = $placements->pluck('id');

            $jumlahSiswa = $placementIds->count();

            $jurnalPending = Journal::whereIn('placement_id', $placementIds)
                ->where('status', 'pending')
                ->count();

            $hadirHariIni = Attendance::whereIn('placement_id', $placementIds)
                ->whereDate('date', today())
                ->where('status', 'hadir')
                ->count();

            $grafikKehadiran = Attendance::selectRaw(
                'MONTH(date) as month, COUNT(*) as total'
            )
                ->whereIn('placement_id', $placementIds)
                ->where('status', 'hadir')
                ->whereYear('date', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $jurnalStatus = Journal::selectRaw('status, COUNT(*) as total')
                ->whereIn('placement_id', $placementIds)
                ->groupBy('status')
                ->pluck('total', 'status');

            $absensiTerbaru = Attendance::with('student')
                ->whereIn('placement_id', $placementIds)
                ->latest('date')
                ->latest('id')
                ->take(5)
                ->get();

            $jurnalTerbaru = Journal::with('student')
                ->whereIn('placement_id', $placementIds)
                ->latest('date')
                ->latest('id')
                ->take(5)
                ->get();

            return view('pembimbing.dashboard', compact(
                'jumlahSiswa',
                'jurnalPending',
                'hadirHariIni',
                'grafikKehadiran',
                'jurnalStatus',
                'absensiTerbaru',
                'jurnalTerbaru'
            ));
        }

        // DASHBOARD SISWA
        $placement = Placement::with(['location', 'pembimbing'])
            ->where('student_id', $user->id)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->latest('start_date')
            ->first();

        $absenHariIni = null;
        $jadwalPkl = collect();

        if ($placement) {
            $absenHariIni = Attendance::where('placement_id', $placement->id)
                ->whereDate('date', today())
                ->first();

            $jadwalPkl = Schedule::where('placement_id', $placement->id)
                ->whereDate('date', '>=', today())
                ->orderBy('date')
                ->orderBy('start_time')
                ->take(7)
                ->get();
        }

        $jurnalPending = Journal::where('student_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $jurnalRevisi = Journal::where('student_id', $user->id)
            ->where('status', 'revision')
            ->count();

        return view('student.dashboard', compact(
            'placement',
            'absenHariIni',
            'jurnalPending',
            'jurnalRevisi',
            'jadwalPkl'
        ));
    })->name('dashboard');


    // DATA DASHBOARD ADMIN
    Route::get('/api/dashboard/chart-data', [DashboardController::class, 'getAttendanceChartData']);
    Route::get('/api/dashboard/summary', [DashboardController::class, 'getSummaryStats']);


    // PROFIL
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // ADMIN
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('locations', LocationController::class)->except(['show']);
        Route::resource('placements', PlacementController::class)->except(['show']);

        Route::get('/rekap', [RekapController::class, 'index'])->name('rekap');
        Route::get('/rekap/export/{format}', [RekapController::class, 'export'])->name('rekap.export');
        Route::get('/jurnal', [RekapController::class, 'jurnal'])->name('jurnal');

        Route::get('/schedule', [ScheduleController::class, 'index'])
            ->name('schedule.index');

        Route::post('/schedule/generate', [ScheduleController::class, 'generateMonthly'])
            ->name('schedule.generate');
    });


    // SISWA
    Route::middleware('role:student')->prefix('siswa')->name('student.')->group(function () {
        Route::get('/absen', [AbsenController::class, 'index'])->name('absen');
        Route::post('/absen/masuk', [AbsenController::class, 'masuk'])->name('absen.masuk');
        Route::post('/absen/pulang', [AbsenController::class, 'pulang'])->name('absen.pulang');

        Route::get('/riwayat', [AbsenController::class, 'riwayat'])->name('riwayat');

        Route::get('/izin', [AbsenController::class, 'izinCreate'])->name('izin.create');
        Route::post('/izin', [AbsenController::class, 'izinStore'])->name('izin.store');

        Route::resource('jurnal', StudentJurnalController::class)
            ->except(['show', 'destroy'])
            ->parameters(['jurnal' => 'journal']);

        Route::get('/nilai', [NilaiController::class, 'show'])->name('nilai');
    });


    // PEMBIMBING KANTOR DAN SEKOLAH
    Route::middleware('role:pembimbing_kantor,pembimbing_sekolah')
        ->prefix('pembimbing')->name('pembimbing.')->group(function () {

            Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
            Route::get('/siswa/{placement}', [SiswaController::class, 'show'])->name('siswa.show');

            Route::get('/penilaian/{placement}', [PenilaianController::class, 'form'])->name('penilaian.form');
            Route::post('/penilaian/{placement}', [PenilaianController::class, 'store'])->name('penilaian.store');

            Route::post('/jurnal/{journal}/paraf', [ParafController::class, 'paraf'])->name('jurnal.paraf');
            Route::post('/jurnal/{journal}/revisi', [ParafController::class, 'revisi'])->name('jurnal.revisi');
        });


    // KHUSUS PEMBIMBING SEKOLAH (AGISTA)
    Route::middleware('role:pembimbing_sekolah')
        ->prefix('pembimbing')->name('pembimbing.sekolah.')->group(function () {

            Route::get('/jadwal-sekolah', [JadwalSekolahController::class, 'index'])
                ->name('jadwal.index');
        });

    Route::middleware('role:pembimbing_sekolah')
        ->prefix('pembimbing')->name('pembimbing.')->group(function () {

            Route::get('/rekap/absensi', [RekapSekolahController::class, 'absensi'])
                ->name('rekap.absensi');

            Route::get('/rekap/jurnal', [RekapSekolahController::class, 'jurnal'])
                ->name('rekap.jurnal');
        });


    // KHUSUS PEMBIMBING KANTOR (AZMI)
    Route::middleware('role:pembimbing_kantor')
        ->prefix('pembimbing')->name('pembimbing.')->group(function () {

            Route::get('/riwayat-absensi', [RekapKantorController::class, 'absensi'])
                ->name('kantor.absensi');

            Route::get('/jurnal-harian', [RekapKantorController::class, 'jurnal'])
                ->name('kantor.jurnal');

            Route::get('/jadwal', [JadwalPembimbingController::class, 'index'])
                ->name('jadwal.index');

            Route::put('/jadwal/{id}', [JadwalPembimbingController::class, 'update'])
                ->name('jadwal.update');
        });

});
