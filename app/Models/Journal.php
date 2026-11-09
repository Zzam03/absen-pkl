<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = [
        'student_id', 'placement_id', 'date', 'activity', 'photo', 'status',
        'pembimbing_signed_at', 'pembimbing_signature', 'signed_by', 'revision_note',
    ];

    protected $casts = ['date' => 'date', 'pembimbing_signed_at' => 'datetime'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function placement()
    {
        return $this->belongsTo(Placement::class);
    }

    // Pembimbing yang memparaf
    public function signer()
    {
        return $this->belongsTo(User::class, 'signed_by');
    }
}