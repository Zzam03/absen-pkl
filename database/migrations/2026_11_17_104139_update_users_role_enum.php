<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    public function up(): void {
        DB::table('users')->where('role', 'peserta')->update(['role' => 'student']);
        DB::table('users')->where('role', 'pembimbing')->update(['role' => 'pembimbing_kantor']);
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'pembimbing_kantor', 'pembimbing_sekolah', 'student'])->change();
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'peserta'])->change();
        });
    }
};