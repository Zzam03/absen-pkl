<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('placement_id')->unique()->constrained('placements')->cascadeOnDelete();
            $table->unsignedTinyInteger('discipline_score');
            $table->unsignedTinyInteger('skill_score');
            $table->unsignedTinyInteger('attitude_score');
            $table->unsignedTinyInteger('report_score');
            $table->decimal('final_score', 5, 2);
            $table->text('pembimbing_note')->nullable();
            $table->foreignId('scored_by')->constrained('users');
            $table->timestamp('scored_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};