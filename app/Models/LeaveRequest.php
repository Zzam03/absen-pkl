<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model {
    protected $guarded = ['id'];
    public function student() { return $this->belongsTo(User::class, 'student_id'); }
    public function decider() { return $this->belongsTo(User::class, 'decided_by'); }
}