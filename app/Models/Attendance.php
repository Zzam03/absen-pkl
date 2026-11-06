<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id', 'tanggal', 'jam_masuk', 'jam_pulang', 'status', 'keterangan',
    ];

    // Supaya $absen->tanggal bisa langsung ->format('d-m-Y')
    protected $casts = ['tanggal' => 'date'];

    // Satu absen dimiliki satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}