<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('placement_id')->constrained('placements')->cascadeOnDelete();
            $table->date('date');
            $table->text('activity');
            $table->string('photo')->nullable();
            $table->enum('status', ['pending', 'approved', 'revision'])->default('pending');
            $table->timestamp('pembimbing_signed_at')->nullable();
            $table->text('pembimbing_signature')->nullable();
            $table->foreignId('signed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('revision_note')->nullable();
            $table->timestamps();

            // 1 jurnal per siswa per hari
            $table->unique(['student_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};