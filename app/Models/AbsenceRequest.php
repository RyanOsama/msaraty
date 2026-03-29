<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenceRequest extends Model
{
    use HasFactory;
    protected $fillable = [
    'student_id',
    'date',
    'type'
];

public function student()
{
    return $this->belongsTo(Student::class);
}
}