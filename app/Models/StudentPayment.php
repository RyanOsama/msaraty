<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_image',
        'bank_name',
        'receipt_number',
        'amount',
        'status',
        'student_id',
        'rejection_reason',
        'for_month',
    ];

    // 🔗 العلاقة مع الطالب
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}