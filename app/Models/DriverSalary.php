<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverSalary extends Model
{
    protected $fillable = [

        'driver_id',

        'amount',

        'for_month',

        'status',

    ];

    public function driver()
    {
        return $this->belongsTo(
            Driver::class
        );
    }
}