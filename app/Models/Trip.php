<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'trip_name',
        'trip_type',
           'status', 
        'trip_date',
        'trip_time',
        'deadline',
        'assign_id',
        // 'route_id',
        'bus_id',
        'driver_id',
        'created_by',
    ];

public function students()
{
    return $this->belongsToMany(Student::class, 'trip_student')
        ->withPivot('status')
        ->withTimestamps();
}
public function driver()
{
    return $this->belongsTo(Driver::class);
}

public function bus()
{
    return $this->belongsTo(Bus::class);
}
public function assign()
{
    return $this->belongsTo(RouteStation::class, 'assign_id');
}
public function cancellations()
{
    return $this->hasMany(TripCancellation::class);
}

}
