<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Placement extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi yang sudah ada (sesuaikan jika ada perbedaan)
    public function student() { return $this->belongsTo(User::class, 'student_id'); }
    public function mentor() { return $this->belongsTo(User::class, 'pembimbing_id'); }
    public function location() { return $this->belongsTo(Location::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }

    // Relasi Baru
    public function schedules() 
    { 
        return $this->hasMany(Schedule::class); 
    }
}