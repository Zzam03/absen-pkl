<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'peserta')
            ->update(['role' => 'student']);

        DB::table('users')
            ->where('role', 'pembimbing')
            ->update(['role' => 'pembimbing_kantor']);
    }

    public function down(): void
    {
        // Tidak mengubah kembali role agar data tetap aman.
    }
};
