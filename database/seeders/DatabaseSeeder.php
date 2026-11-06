<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // updateOrCreate: aman dijalankan berulang, tidak membuat admin dobel
        User::updateOrCreate(
            ['email' => 'admin@absen.test'],
            [
                'name'     => 'Admin Pembimbing',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );
    }
}