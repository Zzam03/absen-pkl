<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Placement extends Model {
    protected $guarded = ['id'];
    public function student() { return $this->belongsTo(User::class, 'student_id'); }
    public function mentor() { return $this->belongsTo(User::class, 'pembimbing_id'); }
    public function location() { return $this->belongsTo(Location::class); }
    public function schedules() { return $this->hasMany(Schedule::class); }
}