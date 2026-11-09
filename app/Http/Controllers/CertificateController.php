<?php

namespace App\Http\Controllers;

use App\Models\Placement;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function show(Placement $placement)
    {
        $user = auth()->user();

        // Hanya admin atau siswa pemilik sertifikat
        abort_unless($user->role === 'admin' || $placement->student_id === $user->id, 403);

        $placement->load(['student', 'location', 'pembimbing', 'score']);

        if ($placement->status !== 'finished' || ! $placement->score) {
            return back()->with('error', 'Sertifikat hanya tersedia untuk PKL yang sudah selesai dan sudah dinilai.');
        }

        $nomor = 'PKL/'.$placement->end_date->format('Y').'/'.str_pad($placement->id, 4, '0', STR_PAD_LEFT);

        return Pdf::loadView('pdf.sertifikat', compact('placement', 'nomor'))
            ->setPaper('a4', 'landscape')
            ->stream('sertifikat-'.$placement->id.'.pdf');
    }
}