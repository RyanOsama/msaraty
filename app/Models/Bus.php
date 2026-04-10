<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;
     protected $fillable = [
        'number_passengers',
        'type_fuel',
        'driver_id',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
    public function trip()
{
    return $this->hasMany(Trip::class);
}
}
