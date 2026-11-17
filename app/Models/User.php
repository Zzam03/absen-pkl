<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'photo', 'school_name', 'is_active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relasi
    public function activePlacement() { return $this->hasOne(Placement::class, 'student_id')->where('status', 'active'); }
    public function attendances() { return $this->hasMany(Attendance::class, 'student_id'); }
    public function journals() { return $this->hasMany(Journal::class, 'student_id'); }

    // Fungsi Baru untuk Cek Role
    public function isAdmin() { return $this->role === 'admin'; }
    public function isPembimbingKantor() { return $this->role === 'pembimbing_kantor'; }
    public function isPembimbingSekolah() { return $this->role === 'pembimbing_sekolah'; }
    public function isStudent() { return $this->role === 'student'; }
}