<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use Illuminate\Http\Request;

class ParafController extends Controller
{
    // Jurnal harus milik siswa bimbingan pembimbing ini dan berstatus pending
    private function cek(Journal $journal)
    {
        abort_unless($journal->placement->pembimbing_id === auth()->id(), 403);

        if ($journal->status !== 'pending') {
            return back()->with('error', 'Jurnal ini tidak sedang menunggu paraf.');
        }

        return null;
    }

    // APPROVE: paraf digital
    public function paraf(Journal $journal)
    {
        if ($tolak = $this->cek($journal)) {
            return $tolak;
        }

        $waktu   = now();
        $payload = $journal->id.'|'.$journal->student_id.'|'.auth()->id().'|'.$waktu->timestamp.'|'.$journal->activity;

        $journal->update([
            'status'               => 'approved',
            'pembimbing_signed_at' => $waktu,
            'signed_by'            => auth()->id(),
            'pembimbing_signature' => hash_hmac('sha256', $payload, config('app.key')),
            'revision_note'        => null,
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
            'status'               => 'revision',
            'revision_note'        => $data['revision_note'],
            'pembimbing_signed_at' => null,
            'signed_by'            => null,
            'pembimbing_signature' => null,
        ]);

        return back()->with('success', 'Jurnal dikembalikan untuk direvisi.');
    }
}