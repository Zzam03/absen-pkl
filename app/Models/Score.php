<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = [
        'placement_id', 'discipline_score', 'skill_score', 'attitude_score',
        'report_score', 'final_score', 'pembimbing_note', 'scored_by', 'scored_at',
    ];

    protected $casts = ['scored_at' => 'datetime'];

    public function placement()
    {
        return $this->belongsTo(Placement::class);
    }

    public function scorer()
    {
        return $this->belongsTo(User::class, 'scored_by');
    }
}