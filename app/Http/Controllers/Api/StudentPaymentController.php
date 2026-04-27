<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentPayment;

class StudentPaymentController extends Controller
{
public function store(Request $request)
{
    $request->validate([
        'receipt_image' => ['required', 'image'],
        'bank_name' => ['required', 'string'],
        'receipt_number' => ['required', 'string'],
        'amount' => ['required', 'numeric'],
        'student_id' => ['required', 'exists:students,id'],
        'for_month' => ['required', 'string'],
    ]);

    // 🔥 هذا هو الحل الصحيح (Laravel way)
    $path = $request->file('receipt_image')->store('payments', 'public');

    $payment = StudentPayment::create([
        'receipt_image' => $path,
        'bank_name' => $request->bank_name,
        'receipt_number' => $request->receipt_number,
        'amount' => $request->amount,
        'student_id' => $request->student_id,
        'for_month' => $request->for_month,
        'status' => 'pending',
    ]);

    return response()->json([
        ...$payment->toArray(),
        'receipt_image' => asset('storage/' . $path)
    ], 201);
}
public function getStudentPayments(Request $request)
{
    $request->validate([
        'student_id' => ['required', 'exists:students,id'],
    ]);

    $payments = StudentPayment::where('student_id', $request->student_id)
        ->latest()
        ->get()
        ->map(function ($payment) {
            return [
                'id' => $payment->id,
                'receipt_image' => asset('storage/' . $payment->receipt_image),
                'bank_name' => $payment->bank_name,
                'receipt_number' => $payment->receipt_number,
                'amount' => $payment->amount,
                'status' => $payment->status,
                'rejection_reason' => $payment->rejection_reason,
                'for_month' => $payment->for_month,
                'created_at' => $payment->created_at,
            ];
        });

    return response()->json(
         $payments
);
}


public function getAllPayments()
{
    $payments = StudentPayment::with('student.user')
        ->latest()
        ->get()
        ->map(function ($payment) {
            return [
                'id' => $payment->id,

                // 🧾 بيانات الدفع
                'receipt_image' => asset('storage/' . $payment->receipt_image),
                'bank_name' => $payment->bank_name,
                'receipt_number' => $payment->receipt_number,
                'amount' => $payment->amount,
                'status' => $payment->status,
                'rejection_reason' => $payment->rejection_reason,
                'for_month' => $payment->for_month,

                // 👨‍🎓 بيانات الطالب
                'student_id' => $payment->student_id,
                

                // ⏱️ تواريخ
                'created_at' => $payment->created_at,
                'updated_at' => $payment->updated_at,
            ];
        });

    return response()->json($payments);
}
}