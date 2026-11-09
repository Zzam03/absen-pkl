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
        abort_unless($placement->pembimbing_id === auth()->id(), 403);
    }

    public function form(Placement $placement)
    {
        $this->cek($placement);
        $placement->load('student');

        $score          = $placement->score;
        $jurnalPending  = $placement->journals()->where('status', 'pending')->count();
        $jurnalApproved = $placement->journals()->where('status', 'approved')->count();

        return view('pembimbing.nilai', compact('placement', 'score', 'jurnalPending', 'jurnalApproved'));
    }

    public function store(Request $request, Placement $placement)
    {
        $this->cek($placement);

        $data = $request->validate([
            'discipline_score' => 'required|integer|between:0,100',
            'skill_score'      => 'required|integer|between:0,100',
            'attitude_score'   => 'required|integer|between:0,100',
            'report_score'     => 'required|integer|between:0,100',
            'pembimbing_note'  => 'nullable|string|max:1000',
        ]);

        // Syarat penilaian: jurnal hanya dihitung kalau sudah diparaf
        if ($placement->journals()->where('status', 'pending')->exists()) {
            return back()->withInput()
                ->with('error', 'Masih ada jurnal yang belum kamu paraf atau revisi. Selesaikan dulu sebelum menilai.');
        }

        // Nilai akhir = rata-rata empat komponen (ubah di sini kalau perusahaan punya bobot)
        $final = round((
            $data['discipline_score'] + $data['skill_score']
            + $data['attitude_score'] + $data['report_score']
        ) / 4, 2);

        Score::updateOrCreate(
            ['placement_id' => $placement->id],
            $data + [
                'final_score' => $final,
                'scored_by'   => auth()->id(),
                'scored_at'   => now(),
            ]
        );

        return redirect()->route('pembimbing.siswa.show', $placement)
            ->with('success', 'Nilai disimpan. Nilai akhir: '.$final);
    }
}