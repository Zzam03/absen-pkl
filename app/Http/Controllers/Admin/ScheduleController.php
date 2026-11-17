<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Placement;
use App\Models\Schedule;

class ScheduleController extends Controller {
    public function generateMonthly(Request $request) {
        $request->validate(['month' => 'required|date_format:Y-m']);
        $startDate = Carbon::parse($request->month)->startOfMonth();
        $endDate = Carbon::parse($request->month)->endOfMonth();
        $placements = Placement::where('status', 'active')->get();

        foreach ($placements as $placement) {
            for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                if ($date->isWeekend()) continue;
                Schedule::firstOrCreate(
                    ['placement_id' => $placement->id, 'date' => $date->format('Y-m-d')],
                    ['start_time' => '08:00:00', 'end_time' => '16:00:00']
                );
            }
        }
        return response()->json(['message' => 'Jadwal sebulan berhasil dibuat!']);
    }
}