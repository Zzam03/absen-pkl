<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use Carbon\Carbon;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JurnalController extends Controller
{
    public function index()
    {
        $jurnals = Journal::where('student_id', auth()->id())
            ->orderByDesc('date')
            ->paginate(10);

        return view('student.jurnal.index', compact('jurnals'));
    }

    public function create()
    {
        return view('student.jurnal.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date'     => 'required|date|before_or_equal:today',
            'activity' => 'required|string|min:10|max:2000',
            'photo'    => 'nullable|image|max:4096',
        ]);

        $placement = auth()->user()->currentPlacement();
        if (! $placement) {
            return back()->with('error', 'Kamu belum punya penempatan PKL yang aktif.');
        }

        $tanggal = Carbon::parse($data['date']);
        if ($tanggal->lt($placement->start_date) || $tanggal->gt($placement->end_date)) {
            return back()->withInput()->with('error', 'Tanggal di luar periode PKL kamu.');
        }

        $sudahAda = Journal::where('student_id', auth()->id())
            ->whereDate('date', $data['date'])->exists();
        if ($sudahAda) {
            return back()->withInput()->with('error', 'Jurnal untuk tanggal itu sudah ada. Edit jurnal yang lama.');
        }

        try {
            Journal::create([
                'student_id'   => auth()->id(),
                'placement_id' => $placement->id,
                'date'         => $data['date'],
                'activity'     => $data['activity'],
                'photo'        => $request->hasFile('photo')
                    ? $request->file('photo')->store('jurnal', 'public')
                    : null,
                'status'       => 'pending',
            ]);
        } catch (UniqueConstraintViolationException $e) {
            return back()->withInput()->with('error', 'Jurnal untuk tanggal itu sudah ada.');
        }

        return redirect()->route('student.jurnal.index')
            ->with('success', 'Jurnal terkirim, menunggu paraf pembimbing.');
    }

    // Hanya pemilik jurnal yang boleh membuka dan mengubahnya
    private function milikSendiri(Journal $journal): void
    {
        abort_unless($journal->student_id === auth()->id(), 403);
    }

    public function edit(Journal $journal)
    {
        $this->milikSendiri($journal);

        if ($journal->status === 'approved') {
            return redirect()->route('student.jurnal.index')
                ->with('error', 'Jurnal yang sudah diparaf tidak bisa diubah.');
        }

        return view('student.jurnal.edit', compact('journal'));
    }

    public function update(Request $request, Journal $journal)
    {
        $this->milikSendiri($journal);

        if ($journal->status === 'approved') {
            return redirect()->route('student.jurnal.index')
                ->with('error', 'Jurnal yang sudah diparaf tidak bisa diubah.');
        }

        $data = $request->validate([
            'activity' => 'required|string|min:10|max:2000',
            'photo'    => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('photo')) {
            if ($journal->photo) {
                Storage::disk('public')->delete($journal->photo);
            }
            $data['photo'] = $request->file('photo')->store('jurnal', 'public');
        }

        // Kirim ulang: kembali ke pending, catatan revisi dibersihkan
        $journal->update($data + ['status' => 'pending', 'revision_note' => null]);

        return redirect()->route('student.jurnal.index')
            ->with('success', 'Jurnal dikirim ulang, menunggu paraf.');
    }
}