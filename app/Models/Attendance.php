<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'student_id', 'placement_id', 'date',
        'check_in_time', 'check_in_lat', 'check_in_lng', 'check_in_distance', 'check_in_photo',
        'check_out_time', 'check_out_lat', 'check_out_lng', 'check_out_photo',
        'status', 'is_outside_area', 'note',
    ];

    protected $casts = ['date' => 'date', 'is_outside_area' => 'boolean'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function placement()
    {
        return $this->belongsTo(Placement::class);
    }
}