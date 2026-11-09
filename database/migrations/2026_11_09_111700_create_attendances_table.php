<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('placement_id')->constrained('placements')->cascadeOnDelete();
            $table->date('date');
            $table->time('check_in_time')->nullable();
            $table->decimal('check_in_lat', 10, 7)->nullable();
            $table->decimal('check_in_lng', 10, 7)->nullable();
            $table->unsignedInteger('check_in_distance')->nullable(); // meter dari lokasi
            $table->string('check_in_photo')->nullable();
            $table->time('check_out_time')->nullable();
            $table->decimal('check_out_lat', 10, 7)->nullable();
            $table->decimal('check_out_lng', 10, 7)->nullable();
            $table->string('check_out_photo')->nullable();
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->boolean('is_outside_area')->default(false);
            $table->text('note')->nullable();
            $table->timestamps();

            // 1 siswa hanya boleh punya 1 data per tanggal
            $table->unique(['student_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};