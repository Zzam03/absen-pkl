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
        // Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@absen.test'],
            [
                'name' => 'Admin HR',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Akun Pembimbing Kantor
        $pembimbing = User::updateOrCreate(
            ['email' => 'pembimbing@absen.test'],
            [
                'name' => 'Pembimbing Satu',
                'password' => Hash::make('password123'),
                'role' => 'pembimbing_kantor',
                'phone' => '081200000001',
                'is_active' => true,
            ]
        );

        // Lokasi PKL PT MSN Bandung
        $lokasi = Location::updateOrCreate(
            ['name' => 'Divisi IT'],
            [
                'address' => 'PT MSN, Jl. Reog No. 18, Turangga, Lengkong, Bandung 40264',
                'latitude' => -6.936764118577241,
                'longitude' => 107.63201004418337,
                'radius_meter' => 100,
            ]
        );

        // Data siswa demo
        $siswa = [
            ['siswa1@absen.test', 'Siswa Satu', 'SMK Negeri 1'],
            ['siswa2@absen.test', 'Siswa Dua', 'SMK Negeri 2'],
        ];

        foreach ($siswa as [$email, $nama, $sekolah]) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $nama,
                    'password' => Hash::make('password123'),
                    'role' => 'student',
                    'school_name' => $sekolah,
                    'is_active' => true,
                ]
            );

            Placement::firstOrCreate(
                [
                    'student_id' => $user->id,
                    'status' => 'active',
                ],
                [
                    'location_id' => $lokasi->id,
                    'pembimbing_id' => $pembimbing->id,
                    'start_date' => today()->subDays(7),
                    'end_date' => today()->addMonths(3),
                ]
            );
        }
    }
}
