<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Placement extends Model
{
    protected $fillable = [
        'student_id', 'location_id', 'pembimbing_id', 'start_date', 'end_date', 'status',
    ];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function pembimbing()
    {
        return $this->belongsTo(User::class, 'pembimbing_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    public function score()
    {
        return $this->hasOne(Score::class);
    }
}   