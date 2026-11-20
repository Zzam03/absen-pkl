<?php
namespace App\Http\Controllers\Pembimbing;
use App\Http\Controllers\Controller;
use App\Models\Placement;
use App\Models\Journal;

class PembimbingController extends Controller {
    public function siswa() {
        $placements = Placement::with(['student', 'location'])->where('pembimbing_id', auth()->id())->get();
        return view('pembimbing.siswa', compact('placements'));
    }
    public function laporan() {
        $journals = Journal::with('student')->orderBy('date', 'desc')->get();
        return view('pembimbing.laporan', compact('journals'));
    }
}