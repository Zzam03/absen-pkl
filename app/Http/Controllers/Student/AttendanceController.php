<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'required|string', // Base64 string dari kamera live
        ]);

        $student = auth()->user();
        $placement = $student->activePlacement;

        if (!$placement) {
            return response()->json(['message' => 'Anda belum memiliki penempatan PKL aktif.'], 403);
        }

        // Rumus Haversine
        $earthRadius = 6371000; // meter
        $latFrom = deg2rad($request->latitude);
        $lonFrom = deg2rad($request->longitude);
        $latTo = deg2rad($placement->location->latitude);
        $lonTo = deg2rad($placement->location->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        $distance = $angle * $earthRadius;

        // VALIDASI KETAT: Tolak jika di luar radius
        if ($distance > $placement->location->radius_meter) {
            return response()->json([
                'message' => 'Absensi Gagal! Anda berada di luar radius area PKL (Jarak Anda: ' . round($distance) . ' meter, Maksimal: ' . $placement->location->radius_meter . ' meter).'
            ], 422);
        }

        // Cek apakah sudah absen hari ini
        $existing = Attendance::where('student_id', $student->id)->whereDate('date', today())->first();
        if ($existing) {
            return response()->json(['message' => 'Anda sudah melakukan absensi hari ini.'], 422);
        }

        // Simpan foto Base64 ke Storage (Folder: storage/app/public/attendances)
        $image_parts = explode(";base64,", $request->photo);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = 'attendance_' . $student->id . '_' . time() . '.jpg';
        Storage::disk('public')->put('attendances/' . $fileName, $image_base64);

        // Simpan Absen
        Attendance::create([
            'student_id' => $student->id,
            'placement_id' => $placement->id,
            'date' => now()->toDateString(),
            'check_in_time' => now()->toTimeString(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'distance' => $distance,
            'photo' => 'attendances/' . $fileName,
            'status' => 'hadir'
        ]);

        return response()->json(['message' => 'Absensi Berhasil! Terima kasih.']);
    }
}