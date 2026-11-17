<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller {
    public function store(Request $request) {
        $request->validate(['latitude' => 'required|numeric', 'longitude' => 'required|numeric', 'photo' => 'required|string']);
        $student = auth()->user();
        $placement = $student->activePlacement;

        if (!$placement) return response()->json(['message' => 'Anda belum memiliki penempatan PKL aktif.'], 403);

        $earthRadius = 6371000;
        $latFrom = deg2rad($request->latitude); $lonFrom = deg2rad($request->longitude);
        $latTo = deg2rad($placement->location->latitude); $lonTo = deg2rad($placement->location->longitude);
        $latDelta = $latTo - $latFrom; $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        $distance = $angle * $earthRadius;

        if ($distance > $placement->location->radius_meter) {
            return response()->json(['message' => 'Absensi Gagal! Anda di luar radius (' . round($distance) . 'm / maks ' . $placement->location->radius_meter . 'm).'], 422);
        }

        $image_parts = explode(";base64,", $request->photo);
        $fileName = 'attendance_' . $student->id . '_' . time() . '.jpg';
        Storage::disk('public')->put('attendances/' . $fileName, base64_decode($image_parts[1]));

        Attendance::create([
            'student_id' => $student->id, 'placement_id' => $placement->id, 'date' => now()->toDateString(),
            'check_in_time' => now()->toTimeString(), 'latitude' => $request->latitude, 'longitude' => $request->longitude,
            'distance' => $distance, 'photo' => 'attendances/' . $fileName, 'status' => 'hadir'
        ]);
        return response()->json(['message' => 'Absensi Berhasil!']);
    }
}