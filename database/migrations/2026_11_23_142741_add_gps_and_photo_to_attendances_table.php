<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Hanya tambahkan kolom yang benar-benar belum ada
            $table->decimal('latitude', 10, 7)->nullable()->after('placement_id');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->float('distance')->nullable()->after('longitude');
            $table->string('photo')->nullable()->after('distance');
            // Kolom 'note' TIDAK ditambahkan di sini karena sudah ada
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'distance', 'photo']);
        });
    }
};