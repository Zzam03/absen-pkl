<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Journal;
use App\Models\Placement;
use App\Models\User;
use Illuminate\Http\Request;

class RekapKantorController extends Controller
{
    /**
     * Mengambil penempatan siswa yang dibimbing oleh pembimbing kantor.
     */
    private function placements()
    {
        $user = auth()->user();

        abort_unless(
            $user && $user->role === 'pembimbing_kantor',
            403
        );

        return Placement::where('pembimbing_id', $user->id)
            ->whereHas('student', function ($query) {
                $query->where('role', 'student');
            });
    }

    /**
     * Mengambil daftar siswa bimbingan Azmi.
     */
    private function students()
    {
        $studentIds = $this->placements()
            ->pluck('student_id')
            ->unique()
            ->values();

        return User::whereIn('id', $studentIds)
            ->where('role', 'student')
            ->orderBy('name')
            ->get();
    }

    /**
     * Riwayat absensi siswa bimbingan.
     */
    public function absensi(Request $request)
    {
        $request->validate([
            'dari' => 'nullable|date',
            'sampai' => 'nullable|date|after_or_equal:dari',
            'status' => 'nullable|in:hadir,izin,sakit,alpha',
            'student_id' => 'nullable|integer',
        ]);

        $students = $this->students();

        $placementIds = $this->placements()->select('id');

        $query = Attendance::with([
                'student',
                'placement.location',
            ])
            ->whereIn('placement_id', $placementIds)
            ->orderByDesc('date');

        if ($request->filled('dari')) {
            $query->whereDate('date', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('date', '<=', $request->sampai);
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->boolean('outside')) {
            $query->where('is_outside_area', true);
        }

        $attendances = $query
            ->paginate(15)
            ->withQueryString();

        return view(
            'pembimbing.kantor.absensi',
            compact('attendances', 'students')
        );
    }

    /**
     * Jurnal harian siswa bimbingan.
     */
    public function jurnal(Request $request)
    {
        $request->validate([
            'dari' => 'nullable|date',
            'sampai' => 'nullable|date|after_or_equal:dari',
            'status' => 'nullable|in:pending,approved,revision',
            'student_id' => 'nullable|integer',
        ]);

        $students = $this->students();

        $placementIds = $this->placements()->select('id');

        $query = Journal::with([
                'student',
                'signer',
                'placement',
            ])
            ->whereIn('placement_id', $placementIds)
            ->orderByDesc('date');

        if ($request->filled('dari')) {
            $query->whereDate('date', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('date', '<=', $request->sampai);
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $journals = $query
            ->paginate(15)
            ->withQueryString();

        return view(
            'pembimbing.kantor.jurnal',
            compact('journals', 'students')
        );
    }
}
