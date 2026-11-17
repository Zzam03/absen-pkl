<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Placement;

class DashboardController extends Controller {
    public function getAttendanceChartData() {
        $data = Attendance::selectRaw('MONTH(date) as month, COUNT(*) as total')
            ->where('status', 'hadir')->whereYear('date', date('Y'))
            ->groupBy('month')->orderBy('month')->get();
        return response()->json($data);
    }
    public function getSummaryStats() {
        return response()->json([
            'total_siswa' => User::where('role', 'student')->count(),
            'total_pembimbing' => User::whereIn('role', ['pembimbing_kantor', 'pembimbing_sekolah'])->count(),
            'penempatan_aktif' => Placement::where('status', 'active')->count(),
            'hadir_hari_ini' => Attendance::whereDate('date', today())->where('status', 'hadir')->count(),
        ]);
    }
}