<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalSekolahController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'month' => 'nullable|date_format:Y-m',
        ]);

        $user = auth()->user();

        $month = $request->input('month', now()->format('Y-m'));

        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $schedules = Schedule::with([
            'placement.student',
            'placement.location',
            'placement.pembimbing',
        ])
            ->whereBetween('date', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
            ])
            ->whereHas('placement', function ($query) use ($user) {
                $query->where('status', 'active')
                    ->whereHas('student', function ($studentQuery) use ($user) {
                        $studentQuery
                            ->where('role', 'student')
                            ->where('school_name', $user->school_name);
                    });
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->paginate(20)
            ->withQueryString();

        return view('pembimbing.sekolah.jadwal.index', compact(
            'schedules',
            'month'
        ));
    }
}
