<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Placement;
use App\Models\Score;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    private function cek(Placement $placement): void
    {
        $user = auth()->user();

        $placement->loadMissing('student');

        if (!$placement->student) {
            abort(404);
        }

        if ($user->role === 'pembimbing_kantor') {
            abort_unless(
                $placement->pembimbing_id === $user->id,
                403
            );
        } elseif ($user->role === 'pembimbing_sekolah') {
            abort_unless(
                $user->school_name &&
                $placement->student->school_name === $user->school_name,
                403
            );
        } else {
            abort(403);
        }
    }

    public function form(Placement $placement)
    {
        $this->cek($placement);

        $placement->load('student');

        $score = $placement->score;

        $jurnalPending = $placement->journals()
            ->where('status', 'pending')
            ->count();

        $jurnalApproved = $placement->journals()
            ->where('status', 'approved')
            ->count();

        return view(
            'pembimbing.nilai',
            compact('placement', 'score', 'jurnalPending', 'jurnalApproved')
        );
    }

    public function store(Request $request, Placement $placement)
    {
        $this->cek($placement);

        $data = $request->validate([
            'discipline_score' => 'required|integer|between:0,100',
            'skill_score' => 'required|integer|between:0,100',
            'attitude_score' => 'required|integer|between:0,100',
            'report_score' => 'required|integer|between:0,100',
            'pembimbing_note' => 'nullable|string|max:1000',
        ]);

        // Penilaian hanya bisa dilakukan jika semua jurnal sudah diproses
        if ($placement->journals()->where('status', 'pending')->exists()) {
            return back()->withInput()
                ->with('error', 'Masih ada jurnal yang belum diparaf atau direvisi. Selesaikan dulu sebelum menilai.');
        }

        // Nilai akhir adalah rata-rata empat komponen
        $final = round((
            $data['discipline_score']
            + $data['skill_score']
            + $data['attitude_score']
            + $data['report_score']
        ) / 4, 2);

        Score::updateOrCreate(
            ['placement_id' => $placement->id],
            $data + [
                'final_score' => $final,
                'scored_by' => auth()->id(),
                'scored_at' => now(),
            ]
        );

        return redirect()
            ->route('pembimbing.siswa.show', $placement)
            ->with('success', 'Nilai disimpan. Nilai akhir: '.$final);
    }
}
