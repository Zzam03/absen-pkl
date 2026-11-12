<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Placement;
use Illuminate\Http\Request;

class AbsenController extends Controller
{
    // Mengambil penempatan aktif milik siswa
    private function penempatanAktif()
    {
        return Placement::with('location')
            ->where('student_id', auth()->id())
            ->where('status', 'active')
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->latest('start_date')
            ->first();
    }

    // Menghitung jarak antara GPS siswa dan lokasi PKL
    private function hitungJarak($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1))
            * cos(deg2rad($lat2))
            * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return (int) round($earthRadius * $c);
    }

    // HALAMAN ABSENSI
    public function index()
    {
        $placement = $this->penempatanAktif();

        $attendance = null;

        if ($placement) {
            $attendance = Attendance::where('student_id', auth()->id())
                ->whereDate('date', today())
                ->first();
        }

        return view('student.absen', compact('placement', 'attendance'));
    }

    // ABSEN MASUK
    public function masuk(Request $request)
    {
        $placement = $this->penempatanAktif();

        if (!$placement) {
            return back()->with('error', 'Kamu tidak memiliki penempatan PKL yang aktif hari ini.');
        }

        $data = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'photo'     => 'nullable|image|max:4096',
        ]);

        $attendance = Attendance::where('student_id', auth()->id())
            ->whereDate('date', today())
            ->first();

        if ($attendance && in_array($attendance->status, ['izin', 'sakit'])) {
            return back()->with('error', 'Absensi hari ini sudah tercatat sebagai izin atau sakit.');
        }

        if ($attendance && $attendance->check_in_time) {
            return back()->with('error', 'Kamu sudah melakukan absen masuk hari ini.');
        }

        $location = $placement->location;

        $distance = $this->hitungJarak(
            (float) $data['latitude'],
            (float) $data['longitude'],
            (float) $location->latitude,
            (float) $location->longitude
        );

        $outsideArea = $distance > $location->radius_meter;

        $photoPath = $request->file('photo')
            ? $request->file('photo')->store('absensi', 'public')
            : null;

        if (!$attendance) {
            $attendance = new Attendance();
            $attendance->student_id = auth()->id();
            $attendance->placement_id = $placement->id;
            $attendance->date = today();
        }

        $attendance->check_in_time = now()->format('H:i:s');
        $attendance->check_in_lat = $data['latitude'];
        $attendance->check_in_lng = $data['longitude'];
        $attendance->check_in_distance = $distance;
        $attendance->check_in_photo = $photoPath;
        $attendance->status = 'hadir';
        $attendance->is_outside_area = $outsideArea;
        $attendance->save();

        return back()->with(
            'success',
            $outsideArea
                ? 'Absen masuk tercatat, tetapi lokasi kamu berada di luar radius PKL.'
                : 'Absen masuk berhasil dicatat.'
        );
    }

    // ABSEN PULANG
    public function pulang(Request $request)
    {
        $placement = $this->penempatanAktif();

        if (!$placement) {
            return back()->with('error', 'Kamu tidak memiliki penempatan PKL yang aktif hari ini.');
        }

        $data = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'photo'     => 'nullable|image|max:4096',
        ]);

        $attendance = Attendance::where('student_id', auth()->id())
            ->where('placement_id', $placement->id)
            ->whereDate('date', today())
            ->first();

        if (!$attendance || !$attendance->check_in_time) {
            return back()->with('error', 'Kamu harus absen masuk terlebih dahulu.');
        }

        if ($attendance->check_out_time) {
            return back()->with('error', 'Kamu sudah melakukan absen pulang hari ini.');
        }

        $location = $placement->location;

        $distance = $this->hitungJarak(
            (float) $data['latitude'],
            (float) $data['longitude'],
            (float) $location->latitude,
            (float) $location->longitude
        );

        $outsideArea = $distance > $location->radius_meter;

        $photoPath = $request->file('photo')
            ? $request->file('photo')->store('absensi', 'public')
            : null;

        $attendance->check_out_time = now()->format('H:i:s');
        $attendance->check_out_lat = $data['latitude'];
        $attendance->check_out_lng = $data['longitude'];
        $attendance->check_out_photo = $photoPath;

        if ($outsideArea) {
            $attendance->is_outside_area = true;
        }

        $attendance->save();

        return back()->with(
            'success',
            $outsideArea
                ? 'Absen pulang tercatat, tetapi lokasi kamu berada di luar radius PKL.'
                : 'Absen pulang berhasil dicatat.'
        );
    }

    // RIWAYAT ABSENSI
    public function riwayat()
    {
        $attendances = Attendance::where('student_id', auth()->id())
            ->orderByDesc('date')
            ->paginate(10);

        return view('student.riwayat', compact('attendances'));
    }

    // HALAMAN PENGAJUAN IZIN / SAKIT
    public function izinCreate()
    {
        $placement = $this->penempatanAktif();

        $attendance = null;

        if ($placement) {
            $attendance = Attendance::where('student_id', auth()->id())
                ->whereDate('date', today())
                ->first();
        }

        return view('student.izin', compact('placement', 'attendance'));
    }

    // SIMPAN IZIN / SAKIT
    public function izinStore(Request $request)
    {
        $placement = $this->penempatanAktif();

        if (!$placement) {
            return back()->with('error', 'Kamu tidak memiliki penempatan PKL yang aktif hari ini.');
        }

        $data = $request->validate([
            'status' => 'required|in:izin,sakit',
            'note'   => 'required|string|min:5|max:1000',
        ]);

        $attendance = Attendance::where('student_id', auth()->id())
            ->whereDate('date', today())
            ->first();

        if ($attendance) {
            return back()->with('error', 'Absensi hari ini sudah tercatat dan tidak dapat diajukan kembali.');
        }

        Attendance::create([
            'student_id'  => auth()->id(),
            'placement_id' => $placement->id,
            'date'        => today(),
            'status'      => $data['status'],
            'note'        => $data['note'],
        ]);

        return redirect()->route('student.riwayat')
            ->with('success', 'Pengajuan izin/sakit berhasil dicatat.');
    }
}
