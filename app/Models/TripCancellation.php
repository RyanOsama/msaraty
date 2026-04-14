<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripCancellation extends Model
{
    use HasFactory;
   protected $fillable = [
    'user_id',
    'trip_id',
    'reason',
    'status',
    'cancelled_at',
];
public function user()
{
    return $this->belongsTo(User::class);
}

public function trip()
{
    return $this->belongsTo(Trip::class);
}
}
