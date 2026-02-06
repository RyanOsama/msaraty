<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = ['name'];

    public function stations()
    {
        return $this->belongsToMany(Station::class, 'route_station');
    }
}
