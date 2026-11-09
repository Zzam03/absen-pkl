<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student'); // student, pembimbing, admin
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->string('school_name')->nullable(); // khusus siswa
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'photo', 'school_name', 'is_active']);
        });
    }
};