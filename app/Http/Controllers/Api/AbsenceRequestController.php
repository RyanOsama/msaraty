<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AbsenceRequestController extends Controller
{
    public function index()
{
    $absences = \App\Models\AbsenceRequest::select(
        'id',
        'student_id',
        'date',
        'type',
        'created_at'
    )->orderBy('id', 'desc')->get();

    return response()->json($absences, 200);
}



public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'date' => 'required|date',
        'type' => 'required|in:pickup,dropoff',
    ]);

    // نجيب الطالب من user_id
    $student = \App\Models\Student::where('user_id', $request->user_id)->first();

    if (!$student) {
        return response()->json([
            'message' => 'Student not found'
        ], 404);
    }

    $absence = \App\Models\AbsenceRequest::create([
        'student_id' => $student->id,
        'date' => $request->date,
        'type' => $request->type,
    ]);

    // 👇 نفس أسلوبك (بدون مصفوفة داخل مصفوفة)
    return response()->json([
        'id' => $absence->id,
        'student_id' => $absence->student_id,
        'date' => $absence->date,
        'type' => $absence->type,
    ], 201);
}

}
