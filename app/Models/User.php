<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'photo',
        'school_name',
        'is_active',
        'login_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relasi sebagai siswa
    public function placements()
    {
        return $this->hasMany(Placement::class, 'student_id');
    }

    // Relasi sebagai pembimbing kantor
    public function supervisedPlacements()
    {
        return $this->hasMany(Placement::class, 'pembimbing_id');
    }

    // Penempatan aktif milik siswa
    public function activePlacement()
    {
        return $this->hasOne(Placement::class, 'student_id')
            ->where('status', 'active');
    }

    // Riwayat absensi
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    // Riwayat jurnal
    public function journals()
    {
        return $this->hasMany(Journal::class, 'student_id');
    }

    // Pemeriksaan role
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPembimbingKantor()
    {
        return $this->role === 'pembimbing_kantor';
    }

    public function isPembimbingSekolah()
    {
        return $this->role === 'pembimbing_sekolah';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }
}
