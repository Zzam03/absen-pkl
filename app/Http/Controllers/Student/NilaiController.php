<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class NilaiController extends Controller
{
    public function show()
    {
        // Penempatan terbaru milik siswa (boleh sudah selesai); nilai bisa null
        $placement = auth()->user()->placements()
            ->with(['score', 'pembimbing', 'location'])
            ->orderByDesc('start_date')
            ->first();

        return view('student.nilai', compact('placement'));
    }
}