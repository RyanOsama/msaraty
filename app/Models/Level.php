<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = [
        'level_name',
        'department_id'
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
public function departments()
{
    return $this->belongsToMany(Department::class);
}


}
