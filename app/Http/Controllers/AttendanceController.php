<?php

namespace App\Http\Controllers;

use App\Models\Attendance;

class AttendanceController extends Controller
{
    // Menampilkan halaman absen
    public function index()
    {
        $absenHariIni = Attendance::where('user_id', auth()->id())
            ->whereDate('tanggal', today())
            ->first();

        return view('absen', compact('absenHariIni'));
    }

    // Absen masuk
    public function masuk()
    {
        $sudah = Attendance::where('user_id', auth()->id())
            ->whereDate('tanggal', today())
            ->exists();

        if ($sudah) {
            return back()->with('error', 'Kamu sudah tercatat hari ini!');
        }

        Attendance::create([
            'user_id'   => auth()->id(),
            'tanggal'   => today(),
            'jam_masuk' => now()->format('H:i:s'),
            'status'    => 'hadir',
        ]);

        return back()->with('success', 'Absen masuk berhasil!');
    }

    // Absen pulang
    public function pulang()
    {
        $absen = Attendance::where('user_id', auth()->id())
            ->whereDate('tanggal', today())
            ->first();

        if (!$absen || $absen->status !== 'hadir') {
            return back()->with('error', 'Kamu belum absen masuk!');
        }

        if ($absen->jam_pulang) {
            return back()->with('error', 'Kamu sudah absen pulang!');
        }

        $absen->update(['jam_pulang' => now()->format('H:i:s')]);

        return back()->with('success', 'Absen pulang berhasil!');
    }

    // Riwayat absen milik sendiri
    public function riwayat()
    {
        $riwayat = Attendance::where('user_id', auth()->id())
            ->orderByDesc('tanggal')
            ->paginate(10);

        return view('riwayat', compact('riwayat'));
    }
}