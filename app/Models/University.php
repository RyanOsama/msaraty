<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $fillable = [
        'university_name',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function colleges()
    {
        return $this->hasMany(College::class);
    }
}
