<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Placement;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Journal;
use App\Models\LeaveRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. BUAT AKUN- AKUN UTAMA (4 ROLE)
        // ==========================================

        // Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@absen.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Akun Pembimbing Kantor (Azmi)
        $pembimbingKantor = User::updateOrCreate(
            ['email' => 'azmi@absen.test'],
            [
                'name' => 'Azmi',
                'password' => Hash::make('password123'),
                'role' => 'pembimbing_kantor',
                'phone' => '081200000001',
                'is_active' => true,
            ]
        );

        // Akun Pembimbing Sekolah (Agista)
        $pembimbingSekolah = User::updateOrCreate(
            ['email' => 'agista@absen.test'],
            [
                'name' => 'Agista',
                'password' => Hash::make('password123'),
                'role' => 'pembimbing_sekolah',
                'phone' => '081200000002',
                'school_name' => 'SMK Negeri 1 Bandung',
                'is_active' => true,
            ]
        );

        // ==========================================
        // 2. BUAT LOKASI PKL
        // ==========================================
        $lokasi = Location::updateOrCreate(
            ['name' => 'Divisi IT'],
            [
                'address' => 'PT MSN, Jl. Reog No. 18, Turangga, Lengkong, Bandung 40264',
                'latitude' => -6.936764118577241,
                'longitude' => 107.63201004418337,
                'radius_meter' => 100,
            ]
        );

        // ==========================================
        // 3. BUAT DATA SISWA & PENEMPATAN (Azzam, Alvin, Thomi)
        // ==========================================
        $siswaData = [
            ['azzam@absen.test', 'Azzam'],
            ['alvin@absen.test', 'Alvin'],
            ['thomi@absen.test', 'Thomi'],
        ];

        foreach ($siswaData as $index => [$email, $nama]) {
            $student = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $nama,
                    'password' => Hash::make('password123'),
                    'role' => 'student',
                    'school_name' => 'SMK Negeri 1 Bandung',
                    'is_active' => true,
                ]
            );

            $placement = Placement::updateOrCreate(
                ['student_id' => $student->id, 'status' => 'active'],
                [
                    'location_id' => $lokasi->id,
                    'pembimbing_id' => $pembimbingKantor->id,
                    'start_date' => today()->subDays(30),
                    'end_date' => today()->addMonths(3),
                ]
            );

            // ==========================================
            // 4. BUAT DATA DUMMY UNTUK TESTING FITUR BARU
            // ==========================================

            // A. Buat Riwayat Absensi (3 hari ke belakang)
            for ($i = 1; $i <= 3; $i++) {
                Attendance::updateOrCreate(
                    ['student_id' => $student->id, 'date' => today()->subDays($i)],
                    [
                        'placement_id' => $placement->id,
                        'check_in_time' => '08:00:00',
                        'latitude' => -6.936764118577241,
                        'longitude' => 107.63201004418337,
                        'distance' => 5,
                        'photo' => 'attendances/dummy.jpg',
                        'status' => 'hadir',
                    ]
                );
            }

            // B. Buat Jadwal (3 hari ke depan)
            for ($i = 1; $i <= 3; $i++) {
                Schedule::updateOrCreate(
                    ['placement_id' => $placement->id, 'date' => today()->addDays($i)],
                    [
                        'start_time' => '08:00:00',
                        'end_time' => '16:00:00',
                        'activity' => $i == 2 ? 'Pulang Cepat / Izin' : 'Masuk Normal',
                    ]
                );
            }

            // C. Buat Jurnal & Izin (Hanya untuk siswa pertama: Azzam)
            if ($index == 0) {
                Journal::updateOrCreate(
                    ['student_id' => $student->id, 'date' => today()->subDays(1)],
                    [
                        'placement_id' => $placement->id,
                        'activity' => 'Membuat desain UI untuk fitur login.',
                        'photo' => null,
                        'status' => 'pending',
                    ]
                );

                Journal::updateOrCreate(
                    ['student_id' => $student->id, 'date' => today()->subDays(2)],
                    [
                        'placement_id' => $placement->id,
                        'activity' => 'Memperbaiki bug pada database.',
                        'photo' => null,
                        'status' => 'approved',
                        'signed_by' => $pembimbingKantor->id,
                    ]
                );

                               LeaveRequest::updateOrCreate(
                    ['student_id' => $student->id, 'date' => today()->addDays(2)],
                    [
                        'placement_id' => $placement->id, // Wajib ditambahkan sesuai tabel teman
                        'status' => 'sakit',              // 'type' diganti 'status'
                        'note' => 'Demam dan harus istirahat sesuai surat dokter.', // 'reason' diganti 'note'
                        'approval_status' => 'pending',   // 'status' diganti 'approval_status'
                    ]
                );
            }
        }
    }
}