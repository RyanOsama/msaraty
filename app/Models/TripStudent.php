<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripStudent extends Model
{
    protected $table = 'trip_student';

    protected $fillable = [
        'trip_id',
        'student_id',
        'status',
    ];
}