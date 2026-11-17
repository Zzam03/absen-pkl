<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Journal;

class JournalController extends Controller {
    public function indexStudent() {
        $journals = Journal::where('student_id', auth()->id())->orderBy('date', 'desc')->get();
        return view('siswa.jurnal', compact('journals'));
    }
    public function store(Request $request) {
        $request->validate(['date' => 'required|date|before_or_equal:today', 'activity' => 'required|string']);
        $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('journals', 'public') : null;
        Journal::create(['student_id' => auth()->id(), 'date' => $request->date, 'activity' => $request->activity, 'photo' => $photoPath]);
        return back()->with('success', 'Jurnal berhasil dikirim!');
    }
    public function indexPembimbing() {
        $journals = Journal::with('student')->orderBy('date', 'desc')->get();
        return view('pembimbing.jurnal', compact('journals'));
    }
    public function updateStatus(Request $request, Journal $journal) {
        $request->validate(['status' => 'required|in:approved,revision', 'note' => 'required_if:status,revision']);
        $journal->update(['status' => $request->status, 'note' => $request->note, 'signed_by' => $request->status === 'approved' ? auth()->id() : null]);
        return back()->with('success', 'Status jurnal diperbarui!');
    }
}