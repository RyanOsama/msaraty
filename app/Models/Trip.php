<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'trip_name',
        'trip_type',
        'trip_date',
        'trip_time',
        'deadline',
        'assign_id',
        'route_id',
        'bus_id',
        'driver_id',
        'created_by',
    ];

   public function students()
{
    return $this->belongsToMany(Student::class, 'trip_student');
}
}
