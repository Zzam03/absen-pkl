<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveRequestController extends Controller
{
    // Memeriksa apakah pengajuan milik siswa yang dibimbing
    private function periksaAkses(LeaveRequest $leaveRequest)
    {
        $user = auth()->user();

        $leaveRequest->loadMissing('placement.student');

        $placement = $leaveRequest->placement;
        $student = $placement?->student;

        if (!$placement || !$student) {
            abort(404);
        }

        if ($user->role === 'pembimbing_kantor') {
            abort_unless(
                (int) $placement->pembimbing_id === (int) $user->id,
                403
            );

            return;
        }

        if ($user->role === 'pembimbing_sekolah') {
            abort_unless(
                !empty($user->school_name) &&
                $student->school_name === $user->school_name,
                403
            );

            return;
        }

        abort(403);
    }

    // DAFTAR PENGAJUAN IZIN / SAKIT
    public function index()
    {
        $user = auth()->user();

        $query = LeaveRequest::with([
            'student',
            'placement.location',
            'reviewer',
        ]);

        if ($user->role === 'pembimbing_kantor') {
            $query->whereHas('placement', function ($placementQuery) use ($user) {
                $placementQuery->where('pembimbing_id', $user->id);
            });
        } elseif ($user->role === 'pembimbing_sekolah') {
            $query->whereHas('student', function ($studentQuery) use ($user) {
                $studentQuery->where('school_name', $user->school_name);
            });
        } else {
            abort(403);
        }

        $leaveRequests = $query
            ->orderByRaw("CASE WHEN approval_status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('date')
            ->paginate(15);

        return view('pembimbing.izin.index', compact('leaveRequests'));
    }

    // MENYETUJUI PENGAJUAN
    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $this->periksaAkses($leaveRequest);

        if ($leaveRequest->approval_status !== 'pending') {
            return back()->with(
                'error',
                'Pengajuan ini sudah pernah diproses.'
            );
        }

        $attendanceExists = Attendance::where(
            'student_id',
            $leaveRequest->student_id
        )
            ->whereDate('date', $leaveRequest->date)
            ->exists();

        if ($attendanceExists) {
            return back()->with(
                'error',
                'Pengajuan tidak dapat disetujui karena absensi pada tanggal tersebut sudah tercatat.'
            );
        }

        DB::transaction(function () use ($leaveRequest, $request) {
            $leaveRequest->update([
                'approval_status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'review_note' => $request->input('review_note'),
            ]);

            Attendance::create([
                'student_id' => $leaveRequest->student_id,
                'placement_id' => $leaveRequest->placement_id,
                'date' => $leaveRequest->date,
                'status' => $leaveRequest->status,
                'note' => $leaveRequest->note,
            ]);
        });

        return back()->with(
            'success',
            'Pengajuan berhasil disetujui dan dicatat pada riwayat absensi siswa.'
        );
    }

    // MENOLAK PENGAJUAN
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $this->periksaAkses($leaveRequest);

        if ($leaveRequest->approval_status !== 'pending') {
            return back()->with(
                'error',
                'Pengajuan ini sudah pernah diproses.'
            );
        }

        $data = $request->validate([
            'review_note' => 'nullable|string|max:1000',
        ]);

        $leaveRequest->update([
            'approval_status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_note' => $data['review_note'] ?? null,
        ]);

        return back()->with(
            'success',
            'Pengajuan berhasil ditolak.'
        );
    }
}
