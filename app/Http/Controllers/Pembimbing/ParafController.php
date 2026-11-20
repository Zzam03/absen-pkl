<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use Illuminate\Http\Request;

class ParafController extends Controller
{
    // Memeriksa hak akses dan status jurnal
    private function cek(Journal $journal)
    {
        $user = auth()->user();

        $journal->loadMissing('placement.student');

        $placement = $journal->placement;

        if (!$placement || !$placement->student) {
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

        if ($journal->status !== 'pending') {
            return back()->with(
                'error',
                'Jurnal ini tidak sedang menunggu paraf.'
            );
        }

        return null;
    }

    // APPROVE: paraf digital
    public function paraf(Journal $journal)
    {
        if ($tolak = $this->cek($journal)) {
            return $tolak;
        }

        $waktu = now();

        $payload = $journal->id.'|'.$journal->student_id.'|'
            .auth()->id().'|'.$waktu->timestamp.'|'.$journal->activity;

        $journal->update([
            'status' => 'approved',
            'pembimbing_signed_at' => $waktu,
            'signed_by' => auth()->id(),
            'pembimbing_signature' => hash_hmac(
                'sha256',
                $payload,
                config('app.key')
            ),
            'revision_note' => null,
        ]);

        return back()->with('success', 'Jurnal diparaf.');
    }

    // REVISI: kembalikan ke siswa dengan catatan
    public function revisi(Request $request, Journal $journal)
    {
        $data = $request->validate([
            'revision_note' => 'required|string|max:500',
        ]);

        if ($tolak = $this->cek($journal)) {
            return $tolak;
        }

        $journal->update([
            'status' => 'revision',
            'revision_note' => $data['revision_note'],
            'pembimbing_signed_at' => null,
            'signed_by' => null,
            'pembimbing_signature' => null,
        ]);

        return back()->with(
            'success',
            'Jurnal dikembalikan untuk direvisi.'
        );
    }
}
