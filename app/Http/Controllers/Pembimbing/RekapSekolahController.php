<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Journal;
use App\Models\User;
use Illuminate\Http\Request;

class RekapSekolahController extends Controller
{
    private function students()
    {
        $user = auth()->user();

        abort_unless(
            $user->role === 'pembimbing_sekolah' && $user->school_name,
            403
        );

        return User::where('role', 'student')
            ->where('school_name', $user->school_name)
            ->orderBy('name')
            ->get();
    }

    public function absensi(Request $request)
    {
        $request->validate([
            'dari' => 'nullable|date',
            'sampai' => 'nullable|date|after_or_equal:dari',
            'status' => 'nullable|in:hadir,izin,sakit,alpha',
            'student_id' => 'nullable|integer',
        ]);

        $students = $this->students();
        $studentIds = $students->pluck('id');

        $query = Attendance::with(['student', 'placement.location'])
            ->whereIn('student_id', $studentIds)
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

        $attendances = $query->paginate(15)->withQueryString();

        return view(
            'pembimbing.rekap.absensi',
            compact('attendances', 'students')
        );
    }

    public function jurnal(Request $request)
    {
        $request->validate([
            'dari' => 'nullable|date',
            'sampai' => 'nullable|date|after_or_equal:dari',
            'status' => 'nullable|in:pending,approved,revision',
            'student_id' => 'nullable|integer',
        ]);

        $students = $this->students();
        $studentIds = $students->pluck('id');

        $query = Journal::with(['student', 'signer', 'placement'])
            ->whereIn('student_id', $studentIds)
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

        $journals = $query->paginate(15)->withQueryString();

        return view(
            'pembimbing.rekap.jurnal',
            compact('journals', 'students')
        );
    }
}
