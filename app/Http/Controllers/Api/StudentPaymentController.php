<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PaymentRequest;
use App\Models\StudentPayment;

class StudentPaymentController extends Controller
{


public function index()
{
    return response()->json(
        PaymentRequest::latest()->get()
    );
}
public function studentPaymentsIndex()
{
    return response()->json(
        StudentPayment::latest()->get()
    );
}
    // =====================================================
    // إنشاء طلب دفع جديد
    // =====================================================
    public function store(Request $request)
    {
        $request->validate([
            'receipt_image' => ['required', 'image'],
            'payment_type' => ['required', 'string'],
            'receipt_number' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'student_id' => ['required', 'exists:students,id'],
            'for_month' => ['required', 'string'],
        ]);

        $path = $request->file('receipt_image')
            ->store('payments', 'public');

        $paymentRequest = PaymentRequest::create([
            'receipt_image' => $path,
            'payment_type' => $request->payment_type,
            'receipt_number' => $request->receipt_number,
            'amount' => $request->amount,
            'student_id' => $request->student_id,
            'for_month' => $request->for_month,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'تم إنشاء طلب الدفع بنجاح',
            'payment_request' => [
                'id' => $paymentRequest->id,
                'receipt_image' => asset('storage/' . $path),
                'payment_type' => $paymentRequest->payment_type,
                'receipt_number' => $paymentRequest->receipt_number,
                'amount' => $paymentRequest->amount,
                'student_id' => $paymentRequest->student_id,
                'for_month' => $paymentRequest->for_month,
                'status' => $paymentRequest->status,
            ]
        ], 201);
    }

    // =====================================================
    // طلبات طالب معين
    // =====================================================
    public function getStudentPayments(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
        ]);

        $payments = PaymentRequest::where(
            'student_id',
            $request->student_id
        )
        ->latest()
        ->get();

        return response()->json($payments);
    }

    // =====================================================
    // جميع الطلبات للأدمن
    // =====================================================
    public function getAllPayments()
    {
        $payments = PaymentRequest::latest()->get();

        return response()->json($payments);
    }

   public function update(Request $request)
{
    $request->validate([
        'payment_id' => ['required', 'exists:payment_requests,id'],
        'status' => ['sometimes', 'in:pending,approved,rejected'],
        'rejection_reason' => ['nullable', 'string'],
    ]);

    $paymentRequest = PaymentRequest::findOrFail(
        $request->payment_id
    );

    $paymentRequest->update([
        'status' => $request->status ?? $paymentRequest->status,
        'rejection_reason' => $request->rejection_reason ?? $paymentRequest->rejection_reason,
    ]);

    // إذا صار Approved
    if ($request->status === 'approved') {

        StudentPayment::updateOrCreate(
            [
                'student_id' => $paymentRequest->student_id,
                'for_month' => $paymentRequest->for_month,
            ],
            [
                'status' => 'paid',
                'payment_request_id' => $paymentRequest->id,
                'amount' => $paymentRequest->amount,
            ]
        );
    }

    return response()->json([
        'message' => 'تم تحديث الطلب بنجاح',
        'payment_request' => $paymentRequest->fresh(),
    ]);
}
public function destroy($id)
{
    $payment = PaymentRequest::findOrFail($id);

    // إذا فيه ربط بجدول الدفعات نفكه
    StudentPayment::where(
        'payment_request_id',
        $payment->id
    )->update([
        'payment_request_id' => null,
        'status' => 'unpaid',
    ]);

    // حذف الطلب
    $payment->delete();

    return response()->json([
        'message' => 'تم حذف طلب الدفع بنجاح'
    ]);
}
}