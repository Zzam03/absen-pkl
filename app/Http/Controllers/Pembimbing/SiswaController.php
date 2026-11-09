<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Placement;

class SiswaController extends Controller
{
    // Daftar siswa bimbingan
    public function index()
    {
        $placements = Placement::with(['student', 'location', 'score'])
            ->where('pembimbing_id', auth()->id())
            ->orderByDesc('start_date')
            ->get();

        return view('pembimbing.siswa.index', compact('placements'));
    }

    // Detail siswa: absen + jurnal
    public function show(Placement $placement)
    {
        abort_unless($placement->pembimbing_id === auth()->id(), 403);

        $placement->load(['student', 'location']);

        // Dua tabel berhalaman di satu layar, jadi nama halamannya dibedakan
        $attendances = $placement->attendances()->orderByDesc('date')
            ->paginate(10, ['*'], 'absen_page')->withQueryString();
        $journals = $placement->journals()->orderByDesc('date')
            ->paginate(10, ['*'], 'jurnal_page')->withQueryString();
        $score = $placement->score;

        return view('pembimbing.siswa.show', compact('placement', 'attendances', 'journals', 'score'));
    }
}