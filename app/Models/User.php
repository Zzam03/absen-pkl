<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'role', 'phone', 'photo', 'school_name', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Penempatan milik siswa
    public function placements()
    {
        return $this->hasMany(Placement::class, 'student_id');
    }

    // Penempatan yang dibimbing (untuk pembimbing)
    public function supervisedPlacements()
    {
        return $this->hasMany(Placement::class, 'pembimbing_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function journals()
    {
        return $this->hasMany(Journal::class, 'student_id');
    }

    // Penempatan aktif hari ini (null kalau belum ada atau di luar periode)
    public function currentPlacement()
    {
        return $this->placements()
            ->with('location')
            ->where('status', 'active')
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->first();
    }
}