<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Placement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Aman dijalankan berulang (updateOrCreate / firstOrCreate)
        User::updateOrCreate(
            ['email' => 'admin@absen.test'],
            ['name' => 'Admin HR', 'password' => Hash::make('password123'), 'role' => 'admin']
        );

        $pembimbing = User::updateOrCreate(
            ['email' => 'pembimbing@absen.test'],
            ['name' => 'Pembimbing Satu', 'password' => Hash::make('password123'),
             'role' => 'pembimbing', 'phone' => '081200000001']
        );

        // Koordinat contoh. Ganti nanti lewat menu Lokasi dengan koordinat tempat tesmu.
        $lokasi = Location::firstOrCreate(
            ['name' => 'Divisi IT'],
            ['address' => 'Kantor Pusat', 'latitude' => -6.2000000,
             'longitude' => 106.8166670, 'radius_meter' => 100]
        );

        $siswa = [
            ['siswa1@absen.test', 'Siswa Satu', 'SMK Negeri 1'],
            ['siswa2@absen.test', 'Siswa Dua', 'SMK Negeri 2'],
        ];

        foreach ($siswa as [$email, $nama, $sekolah]) {
            $user = User::updateOrCreate(
                ['email' => $email],
                ['name' => $nama, 'password' => Hash::make('password123'),
                 'role' => 'student', 'school_name' => $sekolah]
            );

            Placement::firstOrCreate(
                ['student_id' => $user->id, 'status' => 'active'],
                ['location_id' => $lokasi->id, 'pembimbing_id' => $pembimbing->id,
                 'start_date' => today()->subDays(7), 'end_date' => today()->addMonths(3)]
            );
        }
    }
}