<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Placement;
use App\Models\Schedule;
use App\Models\LeaveRequest;
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

    // Mengecek apakah jadwal PKL hari ini libur
    private function jadwalHariIniLibur($placement)
    {
        return Schedule::where('placement_id', $placement->id)
            ->whereDate('date', today())
            ->where('status', 'libur')
            ->exists();
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

        if ($this->jadwalHariIniLibur($placement)) {
            return back()->with('error', 'Hari ini merupakan jadwal libur PKL. Kamu tidak perlu melakukan absensi.');
        }

        $data = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'photo'     => 'required|image|max:4096',
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

        if ($this->jadwalHariIniLibur($placement)) {
            return back()->with('error', 'Hari ini merupakan jadwal libur PKL. Kamu tidak perlu melakukan absensi.');
        }

        $data = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            // Foto tidak diperlukan untuk absen pulang.
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

        $leaveRequests = LeaveRequest::with('reviewer')
            ->where('student_id', auth()->id())
            ->orderByDesc('date')
            ->get();

        return view('student.izin', compact(
            'placement',
            'leaveRequests'
        ));
    }

    // SIMPAN PENGAJUAN IZIN / SAKIT
    public function izinStore(Request $request)
    {
        $placement = $this->penempatanAktif();

        if (!$placement) {
            return back()->with('error', 'Kamu tidak memiliki penempatan PKL yang aktif.');
        }

        $data = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'status' => 'required|in:izin,sakit',
            'note' => 'required|string|min:5|max:1000',
        ]);

        $tanggal = $data['date'];

        // Memastikan tanggal berada dalam masa penempatan PKL
        if (
            $tanggal < $placement->start_date->toDateString() ||
            $tanggal > $placement->end_date->toDateString()
        ) {
            return back()->withInput()->with(
                'error',
                'Tanggal berada di luar masa penempatan PKL.'
            );
        }

        // Memeriksa jadwal libur pada tanggal yang dipilih
        $hariLibur = Schedule::where('placement_id', $placement->id)
            ->whereDate('date', $tanggal)
            ->where('status', 'libur')
            ->exists();

        if ($hariLibur) {
            return back()->withInput()->with(
                'error',
                'Tanggal tersebut merupakan jadwal libur PKL.'
            );
        }

        // Memeriksa catatan absensi yang sudah ada
        $attendanceExists = Attendance::where('student_id', auth()->id())
            ->whereDate('date', $tanggal)
            ->exists();

        if ($attendanceExists) {
            return back()->withInput()->with(
                'error',
                'Tanggal tersebut sudah memiliki catatan absensi.'
            );
        }

        // Memeriksa pengajuan sebelumnya
        $requestExists = LeaveRequest::where('student_id', auth()->id())
            ->whereDate('date', $tanggal)
            ->exists();

        if ($requestExists) {
            return back()->withInput()->with(
                'error',
                'Tanggal tersebut sudah pernah diajukan.'
            );
        }

        // Menyimpan pengajuan dengan status menunggu
        LeaveRequest::create([
            'student_id' => auth()->id(),
            'placement_id' => $placement->id,
            'date' => $tanggal,
            'status' => $data['status'],
            'note' => $data['note'],
            'approval_status' => 'pending',
        ]);

        return redirect()
            ->route('student.izin.create')
            ->with(
                'success',
                'Pengajuan berhasil dikirim dan menunggu persetujuan pembimbing.'
            );
    }
}
