
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah role pembimbing lama menjadi pembimbing kantor.
        DB::table('users')
            ->where('role', 'pembimbing')
            ->update(['role' => 'pembimbing_kantor']);
    }

    public function down(): void
    {
        // Tidak mengubah data saat rollback agar role baru
        // tidak ikut berubah menjadi role lama.
    }
};
