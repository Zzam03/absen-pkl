<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'address', 'latitude', 'longitude', 'radius_meter'];

    public function placements()
    {
        return $this->hasMany(Placement::class);
    }
}