<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Tambahkan ini

return new class extends Migration
{
    public function up(): void
    {
        // 1. UBAH DATA LAMA KE FORMAT BARU TERLEBIH DAHULU
        // Ubah 'peserta' menjadi 'student'
        DB::table('users')->where('role', 'peserta')->update(['role' => 'student']);
        
        // Ubah 'pembimbing' menjadi 'pembimbing_kantor'
        DB::table('users')->where('role', 'pembimbing')->update(['role' => 'pembimbing_kantor']);
        
        // (Opsional) Jika ada role aneh lain yang tidak terdeteksi, ubah paksa jadi student
        DB::table('users')->whereNotIn('role', ['admin', 'student', 'pembimbing_kantor', 'pembimbing_sekolah'])->update(['role' => 'student']);

        // 2. BARU UBAH STRUKTUR KOLOM
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'pembimbing_kantor', 'pembimbing_sekolah', 'student'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'peserta', 'pembimbing'])->change();
        });
    }
};