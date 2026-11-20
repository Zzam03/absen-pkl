<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Placement;

class SiswaController extends Controller
{
    // Daftar siswa bimbingan dengan penempatan aktif
    public function index()
    {
        $user = auth()->user();

        $query = Placement::with(['student', 'location', 'score'])
            ->where('status', 'active');

        if ($user->role === 'pembimbing_sekolah') {
            $query->whereHas('student', function ($q) use ($user) {
                $q->where('school_name', $user->school_name);
                $q->where('role', 'student');
            });
        } elseif ($user->role === 'pembimbing_kantor') {
            $query->where('pembimbing_id', $user->id)
                ->whereHas('student', function ($q) {
                    $q->where('role', 'student');
                });
        } else {
            abort(403);
        }

        $placements = $query
            ->orderByDesc('start_date')
            ->get();

        return view('pembimbing.siswa.index', compact('placements'));
    }

    // Detail siswa: absensi dan jurnal
    public function show(Placement $placement)
    {
        $user = auth()->user();

        $placement->load(['student', 'location']);

        abort_unless(
            $placement->status === 'active' &&
            $placement->student &&
            $placement->student->role === 'student',
            404
        );

        if ($user->role === 'pembimbing_sekolah') {
            abort_unless(
                $user->school_name &&
                $placement->student->school_name === $user->school_name,
                403
            );
        } elseif ($user->role === 'pembimbing_kantor') {
            abort_unless(
                $placement->pembimbing_id === $user->id,
                403
            );
        } else {
            abort(403);
        }

        $attendances = $placement->attendances()
            ->orderByDesc('date')
            ->paginate(10, ['*'], 'absen_page')
            ->withQueryString();

        $journals = $placement->journals()
            ->orderByDesc('date')
            ->paginate(10, ['*'], 'jurnal_page')
            ->withQueryString();

        $score = $placement->score;

        return view(
            'pembimbing.siswa.show',
            compact('placement', 'attendances', 'journals', 'score')
        );
    }
}
