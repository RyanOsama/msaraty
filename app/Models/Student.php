<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'university_number',
        'city',
        'gender',
        'state',
        'user_id',
        'university_id',
        'college_id',
        'level_id',
        'department_id',
        'pickup_station_id',
'dropoff_station_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function days()
{
    return $this->belongsToMany(Day::class);
}
public function pickupStation()
{
    return $this->belongsTo(Station::class, 'pickup_station_id');
}

public function dropoffStation()
{
    return $this->belongsTo(Station::class, 'dropoff_station_id');
}

public function absenceRequests()
{
    return $this->hasMany(AbsenceRequest::class);
}
public function trips()
{
    return $this->belongsToMany(Trip::class, 'trip_student');
}
}
