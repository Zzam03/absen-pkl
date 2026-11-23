<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use App\Models\Placement;

class LeaveRequestController extends Controller {
    
    public function store(Request $request) {
        $request->validate(['date' => 'required|date|after_or_equal:today', 'type' => 'required|in:izin,sakit', 'reason' => 'required|string']);

        $existingAttendance = Attendance::where('student_id', auth()->id())->whereDate('date', $request->date)->exists();
        $existingLeave = LeaveRequest::where('student_id', auth()->id())->whereDate('date', $request->date)->where('status', '!=', 'rejected')->exists();

        if ($existingAttendance || $existingLeave) {
            return back()->with('error', 'Anda sudah memiliki data absensi atau pengajuan di tanggal tersebut.');
        }

        LeaveRequest::create(['student_id' => auth()->id(), 'date' => $request->date, 'type' => $request->type, 'reason' => $request->reason, 'status' => 'pending']);
        return back()->with('success', 'Pengajuan berhasil dikirim. Menunggu persetujuan pembimbing.');
    }

    public function indexStudent() {
        $requests = LeaveRequest::where('student_id', auth()->id())->orderBy('date', 'desc')->get();
        return view('siswa.izin', compact('requests'));
    }

    public function indexPembimbing() {
        $studentIds = Placement::where('pembimbing_id', auth()->id())->pluck('student_id');
        $requests = LeaveRequest::with('student')->whereIn('student_id', $studentIds)->orderBy('status', 'asc')->orderBy('date', 'desc')->get();
        return view('pembimbing.izin', compact('requests'));
    }

    public function decide(Request $request, LeaveRequest $leaveRequest) {
        $isMyStudent = Placement::where('student_id', $leaveRequest->student_id)->where('pembimbing_id', auth()->id())->exists();
        if (!$isMyStudent) abort(403, 'Anda bukan pembimbing siswa ini.');
        if ($leaveRequest->status !== 'pending') return back()->with('error', 'Pengajuan sudah diproses sebelumnya.');

        $request->validate(['action' => 'required|in:approved,rejected', 'note' => 'required_if:action,rejected|nullable|string']);

        $leaveRequest->update(['status' => $request->action, 'decided_by' => auth()->id(), 'decided_at' => now(), 'note' => $request->note]);

        if ($request->action === 'approved') {
            $placement = Placement::where('student_id', $leaveRequest->student_id)->where('status', 'active')->first();
            if ($placement) {
                Attendance::updateOrCreate(
                    ['student_id' => $leaveRequest->student_id, 'date' => $leaveRequest->date],
                    ['placement_id' => $placement->id, 'status' => $leaveRequest->type, 'note' => 'Disetujui oleh pembimbing. Alasan: ' . $leaveRequest->reason, 'check_in_time' => null, 'latitude' => null, 'longitude' => null, 'distance' => null, 'photo' => null]
                );
            }
        }
        return back()->with('success', 'Keputusan berhasil disimpan!');
    }
}