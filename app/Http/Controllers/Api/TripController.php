<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip; // 🔥 هذا المهم

class TripController extends Controller
{
    public function index()
{
    $trips = Trip::with('students')->get();

    $result = $trips->map(function ($trip) {
        return [
            'id' => $trip->id,
            'trip_name' => $trip->trip_name,
            'trip_type' => $trip->trip_type,
            'trip_date' => $trip->trip_date,
            'trip_time' => $trip->trip_time,
            'deadline' => $trip->deadline,

            'assign_id' => $trip->assign_id,
            // 'route_id' => $trip->route_id,
            'bus_id' => $trip->bus_id,
            'driver_id' => $trip->driver_id,
            'created_by' => $trip->created_by,

            'student_ids' => $trip->students->pluck('id')->toArray(),

            'created_at' => $trip->created_at,
            'updated_at' => $trip->updated_at,
        ];
    });

    return response()->json($result, 200);
}

public function store(Request $request)
{
    $request->validate([
        'trip_name' => 'required',
        'trip_type' => 'required|in:pickup,dropoff',
        'trip_date' => 'required|date',
        'trip_time' => 'required',
        'deadline' => 'required|date',

        'assign_id' => 'required|exists:route_station,id',
        // 'route_id' => 'required|exists:routes,id',
        'bus_id' => 'required|exists:buses,id',
        'driver_id' => 'required|exists:drivers,id',
        'created_by' => 'required|exists:users,id',

        'student_ids' => 'nullable|array',
        'student_ids.*' => 'exists:students,id',
    ]);

    $trip = Trip::create($request->except('student_ids'));

   $students = [];

foreach ($request->student_ids ?? [] as $studentId) {
   $students[$studentId] = ['status' => 'assigned'];

}

$trip->students()->attach($students);
    return response()->json([
        'status' => true,
        'message' => 'Trip created successfully',
        'data' => $trip->load('students')
    ], 201);
}

public function update(Request $request, $id)
{
    $trip = Trip::findOrFail($id);

    $request->validate([
        'trip_name' => 'sometimes',
        'trip_type' => 'sometimes|in:pickup,dropoff',
        'trip_date' => 'sometimes|date',
        'trip_time' => 'sometimes',
        'deadline' => 'sometimes|date',

        'assign_id' => 'sometimes|exists:route_station,id',
        'route_id' => 'sometimes|exists:routes,id',
        'bus_id' => 'sometimes|exists:buses,id',
        'driver_id' => 'sometimes|exists:drivers,id',
        'created_by' => 'sometimes|exists:users,id',

        'student_ids' => 'nullable|array',
        'student_ids.*' => 'exists:students,id',
    ]);

    $trip->update($request->except('student_ids'));

  $students = [];

foreach ($request->student_ids ?? [] as $studentId) {
    $students[$studentId] = ['status' => 'approved'];
}

$trip->students()->syncWithoutDetaching($students);
    return response()->json([
        'status' => true,
        'message' => 'Trip updated successfully',
        'data' => $trip->load('students')
    ]);
}

public function destroy($id)
{
    $trip = Trip::find($id);

    if (!$trip) {
        return response()->json([
            'status' => false,
            'message' => 'Trip not found'
        ], 404);
    }

    $trip->delete();

    return response()->json([
        'status' => true,
        'message' => 'Trip deleted successfully'
    ]);
}

public function checkStudentTripByDate(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'date' => 'required|date',
    ]);

    $trip = Trip::with(['driver', 'bus']) 
        ->whereDate('trip_date', $request->date)
        ->whereHas('students', function ($q) use ($request) {
            $q->where('student_id', $request->student_id);
        })
        ->first();

    if (!$trip) {
        return response()->json([
            'message' => 'الطالب غير مسجل في أي رحلة بهذا التاريخ',
        ]);
    }


return response()->json([
    'id' => $trip->id,
    'trip_name' => $trip->trip_name,
    'trip_type' => $trip->trip_type,
    'trip_date' => $trip->trip_date,
    'trip_time' => $trip->trip_time,

   
    'route_name' => $trip->assign->route->route_name ?? null,

   
    'driver_name' => $trip->driver->name_driver ?? null,
    'driver_phone' => $trip->driver->phone ?? null,

   
    'bus_number_passengers' => $trip->bus->number_passengers ?? null,
]);
}

public function updateStudentStatus(Request $request)
{
    $request->validate([
        'trip_id' => 'required|exists:trips,id',
        'student_id' => 'required|exists:students,id',
        'status' => 'required|in:assigned,present,absent,excused',
    ]);

    $trip = Trip::findOrFail($request->trip_id);

    // التأكد أن الطالب مرتبط بالرحلة
    if (!$trip->students()->where('student_id', $request->student_id)->exists()) {
        return response()->json([
            'status' => false,
            'message' => 'Student not assigned to this trip'
        ], 404);
    }

    // تحديث الحالة في pivot
    $trip->students()->updateExistingPivot($request->student_id, [
        'status' => $request->status
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Student status updated successfully'
    ]);
}
}
