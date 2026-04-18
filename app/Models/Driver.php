<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;
    
    protected $fillable = [
   
        'state',
        'user_id',
    ];

    public function bus()
    {
        return $this->hasOne(Bus::class);
    }
}
