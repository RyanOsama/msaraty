<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TripCancellation;
use Illuminate\Http\Request;
use App\Models\Student;

class TripCancellationController extends Controller
{

   public function index()
{
    $cancellations = TripCancellation::all();

    return response()->json($cancellations, 200);
}


    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'trip_id' => 'required|exists:trips,id',
        ]);

        $exists = TripCancellation::where('user_id', $request->user_id)
            ->where('trip_id', $request->trip_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'تم إلغاء الرحلة مسبقًا'
            ]);
        }

       $cancel = TripCancellation::create([
    'user_id' => $request->user_id,
    'trip_id' => $request->trip_id,
    'reason' => $request->reason,
    'status' => 'pending',
    'cancelled_at' => now(),
]);

        return response()->json([
            'status' => true,
            'message' => 'تم إلغاء الرحلة بنجاح',
            'data' => [
                'id' => $cancel->id,
                'user_id' => $cancel->user_id,
                'trip_id' => $cancel->trip_id,
                'created_at' => $cancel->created_at,
            ]
        ], 201);
    }




    public function destroy($id)
    {
        $cancel = TripCancellation::find($id);

        if (!$cancel) {
            return response()->json([
                'status' => false,
                'message' => 'Cancellation not found'
            ], 404);
        }

        $cancel->delete();

        return response()->json([
            'message' => 'تم حذف إلغاء الرحلة بنجاح'
        ]);
    }



public function updateStatus(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'trip_id' => 'required|exists:trips,id',
        'status' => 'required|in:pending,approved,rejected',
    ]);

    $student = Student::find($request->student_id);

    $cancel = TripCancellation::where('trip_id', $request->trip_id)
        ->where('user_id', $student->user_id) 
        ->first();

    if (!$cancel) {
        return response()->json([
            'status' => false,
            'message' => 'Cancellation not found'
        ], 404);
    }

    $cancel->status = $request->status;
    $cancel->save();

    if ($request->status === 'approved') {

\DB::table('trip_student')
            ->where('trip_id', $request->trip_id)
            ->where('student_id', $request->student_id)
            ->delete();
    }

    return response()->json([
        'message' => 'تم الغاءالرحله '
    ]);
}
}