<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    // Menampilkan form izin / sakit
    public function create()
    {
        return view('izin');
    }

    // Menyimpan pengajuan izin / sakit
    public function store(Request $request)
    {
        $request->validate([
            'status'     => 'required|in:izin,sakit',
            'keterangan' => 'required|string|max:255',
        ]);

        $sudah = Attendance::where('user_id', auth()->id())
            ->whereDate('tanggal', today())
            ->exists();

        if ($sudah) {
            return back()->with('error', 'Kamu sudah tercatat hari ini!');
        }

        Attendance::create([
            'user_id'    => auth()->id(),
            'tanggal'    => today(),
            'status'     => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('riwayat')->with('success', 'Pengajuan berhasil disimpan!');
    }
}