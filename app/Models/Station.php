<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = ['name', 'location_x', 'location_y', 'description'];

    public function routes()
    {
        return $this->belongsToMany(Route::class, 'route_station');
    }
}
