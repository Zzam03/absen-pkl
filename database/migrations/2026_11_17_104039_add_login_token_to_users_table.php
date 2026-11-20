
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'login_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('login_token', 60)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'login_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('login_token');
            });
        }
    }
};
