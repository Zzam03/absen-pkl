<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;
    protected $fillable = ['name', 'email', 'password', 'role', 'phone', 'photo', 'school_name', 'is_active', 'login_token'];
    protected $hidden = ['password', 'remember_token'];
    
    public function activePlacement() { return $this->hasOne(Placement::class, 'student_id')->where('status', 'active'); }
    public function attendances() { return $this->hasMany(Attendance::class, 'student_id'); }
    public function journals() { return $this->hasMany(Journal::class, 'student_id'); }
}