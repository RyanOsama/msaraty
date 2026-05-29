<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $table = 'payment_requests';

    protected $fillable = [
        'receipt_image',
        'payment_type',
        'receipt_number',
        'amount',
        'status',
        'student_id',
        'rejection_reason',
        'for_month',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}