<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Placement;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    // HALAMAN JADWAL BULANAN
    public function index(Request $request)
    {
        $request->validate([
            'month' => 'nullable|date_format:Y-m',
        ]);

        $month = $request->input('month', now()->format('Y-m'));

        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $schedules = Schedule::with([
            'placement.student',
            'placement.pembimbing',
            'placement.location',
        ])
            ->whereBetween('date', [
                $startDate->format('Y-m-d'),
                $endDate->format('Y-m-d'),
            ])
            ->whereHas('placement', function ($query) {
                $query->where('status', 'active');
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->paginate(20)
            ->withQueryString();

        return view('admin.schedules.index', compact(
            'schedules',
            'month'
        ));
    }

    // GENERATE JADWAL BULANAN
    public function generateMonthly(Request $request)
    {
        $data = $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $startDate = Carbon::createFromFormat('Y-m', $data['month'])
            ->startOfMonth();

        $endDate = $startDate->copy()->endOfMonth();

        $placements = Placement::where('status', 'active')
            ->whereDate('start_date', '<=', $endDate)
            ->whereDate('end_date', '>=', $startDate)
            ->get();

        $created = 0;

        foreach ($placements as $placement) {
            $placementStart = Carbon::parse($placement->start_date);
            $placementEnd = Carbon::parse($placement->end_date);

            $date = $startDate->copy()->max($placementStart);
            $lastDate = $endDate->copy()->min($placementEnd);

            while ($date->lte($lastDate)) {
                if (!$date->isWeekend()) {
                    $schedule = Schedule::firstOrCreate(
                        [
                            'placement_id' => $placement->id,
                            'date' => $date->format('Y-m-d'),
                        ],
                        [
                            'start_time' => '08:00:00',
                            'end_time' => '16:00:00',
                            'status' => 'aktif',
                        ]
                    );

                    if ($schedule->wasRecentlyCreated) {
                        $created++;
                    }
                }

                $date->addDay();
            }
        }

        return redirect()
            ->route('admin.schedule.index', ['month' => $data['month']])
            ->with(
                'success',
                "Generate jadwal selesai. {$created} jadwal baru berhasil dibuat."
            );
    }
}
