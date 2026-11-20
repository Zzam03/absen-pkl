<?php
namespace App\Http\Controllers\Siswa;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Schedule;

class SiswaController extends Controller {
    public function riwayat() {
        $attendances = Attendance::where('student_id', auth()->id())->orderBy('date', 'desc')->get();
        $hadir = $attendances->where('status', 'hadir')->count();
        $total = $attendances->count();
        $persentase = $total > 0 ? round(($hadir / $total) * 100) : 0;
        
        return view('siswa.riwayat', compact('attendances', 'persentase'));
    }
    
    public function jadwal() {
        $placement = auth()->user()->activePlacement;
        $schedules = $placement ? Schedule::where('placement_id', $placement->id)->orderBy('date')->get() : collect();
        return view('siswa.jadwal', compact('schedules'));
    }
}