<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Journal;
use App\Models\Placement;
use App\Models\User;

class DashboardController extends Controller
{
    public function student()
    {
        $user          = auth()->user();
        $placement     = $user->currentPlacement();
        $absenHariIni  = Attendance::where('student_id', $user->id)->whereDate('date', today())->first();
        $jurnalPending = Journal::where('student_id', $user->id)->where('status', 'pending')->count();
        $jurnalRevisi  = Journal::where('student_id', $user->id)->where('status', 'revision')->count();

        return view('student.dashboard', compact('placement', 'absenHariIni', 'jurnalPending', 'jurnalRevisi'));
    }

    public function pembimbing()
    {
        $id      = auth()->id();
        $milikku = fn ($q) => $q->where('pembimbing_id', $id);

        $jumlahSiswa   = Placement::where('pembimbing_id', $id)->where('status', 'active')->count();
        $jurnalPending = Journal::where('status', 'pending')->whereHas('placement', $milikku)->count();
        $hadirHariIni  = Attendance::whereDate('date', today())->where('status', 'hadir')
            ->whereHas('placement', $milikku)->count();

        return view('pembimbing.dashboard', compact('jumlahSiswa', 'jurnalPending', 'hadirHariIni'));
    }

    public function admin()
    {
        $aktifHariIni = Placement::where('status', 'active')
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->count();
        $tercatatHariIni = Attendance::whereDate('date', today())->count();

        $totalSiswa      = User::where('role', 'student')->where('is_active', true)->count();
        $totalPembimbing = User::where('role', 'pembimbing')->where('is_active', true)->count();
        $penempatanAktif = $aktifHariIni;
        $hadirHariIni    = Attendance::whereDate('date', today())->where('status', 'hadir')->count();
        $belumAbsen      = max($aktifHariIni - $tercatatHariIni, 0);
        $diLuarArea      = Attendance::whereDate('date', today())->where('is_outside_area', true)->count();
        $jurnalPending   = Journal::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalSiswa', 'totalPembimbing', 'penempatanAktif',
            'hadirHariIni', 'belumAbsen', 'diLuarArea', 'jurnalPending'
        ));
    }
}