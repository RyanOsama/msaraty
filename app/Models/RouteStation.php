<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteStation extends Model
{
    protected $table = 'route_station';

    protected $fillable = [
        'route_id',
        'station_id',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
