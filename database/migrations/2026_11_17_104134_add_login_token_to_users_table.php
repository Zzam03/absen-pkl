<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('login_token')->nullable()->after('password');
            $table->string('photo')->nullable()->after('phone');
            $table->string('school_name')->nullable()->after('photo');
            $table->boolean('is_active')->default(true)->after('school_name');
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['login_token', 'photo', 'school_name', 'is_active']);
        });
    }
};