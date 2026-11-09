<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AttendanceExport;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Journal;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RekapController extends Controller
{
    private function students()
    {
        return User::where('role', 'student')->orderBy('name')->get();
    }

    // Query rekap absen dengan filter. Dipakai halaman rekap dan export.
    private function queryAbsen(Request $request)
    {
        $request->validate([
            'dari'   => 'nullable|date',
            'sampai' => 'nullable|date|after_or_equal:dari',
            'status' => 'nullable|in:hadir,izin,sakit,alpha',
        ]);

        $query = Attendance::with(['student', 'placement.location'])->orderByDesc('date');

        if ($request->filled('dari')) {
            $query->whereDate('date', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('date', '<=', $request->sampai);
        }
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->boolean('outside')) {
            $query->where('is_outside_area', true);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $attendances = $this->queryAbsen($request)->paginate(15)->withQueryString();
        $students    = $this->students();

        return view('admin.rekap', compact('attendances', 'students'));
    }

    // Export rekap absen (filter ikut terbawa lewat query string)
    public function export(Request $request, string $format)
    {
        $rows = $this->queryAbsen($request)->get();

        if ($format === 'xlsx') {
            return Excel::download(new AttendanceExport($rows), 'rekap-absensi.xlsx');
        }

        return Pdf::loadView('pdf.rekap', ['rows' => $rows])
            ->setPaper('a4', 'landscape')
            ->download('rekap-absensi.pdf');
    }

    // Rekap jurnal semua siswa
    public function jurnal(Request $request)
    {
        $request->validate(['status' => 'nullable|in:pending,approved,revision']);

        $query = Journal::with(['student', 'signer'])->orderByDesc('date');

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $journals = $query->paginate(15)->withQueryString();
        $students = $this->students();

        return view('admin.jurnal', compact('journals', 'students'));
    }
}