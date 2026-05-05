<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\ActivityLog;


class StudentController extends Controller
{
  


public function index()
{
    $students = Student::with([
            'user', // 👈 هذا المهم

        'university',
        'college',
        'department',
        'level',
        'pickupStation',
        'dropoffStation',
                'days'

    ])->get();

    $result = $students->map(function ($student) {

        return [
            'id' => $student->id,
                  'name' => $student->user->full_name ?? null, // 👈 بدل full_name
        'phone' => $student->user->phone ?? null,    // 👈 رقم الجوال
            'university_number' => $student->university_number,
            'city' => $student->city,

            'gender' => in_array($student->gender, ['رجل', 'ذكر']) ? 'ذكر' : 'أنثى',

            'state' => strtolower($student->state) === 'active' ? 'active' : 'inactive',

            'user_id' => $student->user_id,
            'university_id' => $student->university_id,
            'college_id' => $student->college_id,
            'department_id' => $student->department_id,
            'level_id' => $student->level_id,

            'pickup_station_id' => $student->pickup_station_id,
            'dropoff_station_id' => $student->dropoff_station_id,
             'days' => $student->days->pluck('id')->toArray(),

            'created_at' => $student->created_at,
            'updated_at' => $student->updated_at,
        ];
    });

    return response()->json($result, 200);
}




public function show($id)
{
    $student = Student::with([
        'university',
        'college',
        'department',
        'level',
        'pickupStation',
        'dropoffStation',
        'days'
    ])->where('user_id', $id)->first();

    if (!$student) {
        return response()->json([
            'status' => false,
            'message' => 'Student not found'
        ], 404);
    }

    return response()->json([
        'id' => $student->id,
       
        'university_number' => $student->university_number,
        'city' => $student->city,

        'gender' => in_array($student->gender, ['رجل', 'ذكر']) ? 'ذكر' : 'أنثى',

        'state' => strtolower($student->state) === 'active' ? 'active' : 'inactive',

        'user_id' => $student->user_id,
        'university_id' => $student->university_id,
        'college_id' => $student->college_id,
        'department_id' => $student->department_id,
        'level_id' => $student->level_id,

        'pickup_station_id' => $student->pickup_station_id,
        'dropoff_station_id' => $student->dropoff_station_id,

        'days' => $student->days->pluck('id')->toArray(),

        'created_at' => $student->created_at,
        'updated_at' => $student->updated_at,
    ], 200);
}













    
    // إنشاء طالب
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'university_number' => 'required|unique:students',
            'city' => 'nullable',
            'gender' => 'nullable',
            'state' => 'nullable',
            'university_id' => 'required|exists:universities,id',
            'college_id' => 'required|exists:colleges,id',
            'department_id' => 'required|exists:departments,id',
            'level_id' => 'required|exists:levels,id',
          
            'days' => 'nullable|array',
           'days.*' => 'exists:days,id',
'pickup_station_id' => 'nullable|exists:stations,id',
'dropoff_station_id' => 'nullable|exists:stations,id',
        ]);

        $student = Student::create([
           
            'university_number' => $request->university_number,
            'city' => $request->city,
            'gender' => $request->gender,
            'state' => $request->state,
            'university_id' => $request->university_id,
            'college_id' => $request->college_id,
            'department_id' => $request->department_id,
            'level_id' => $request->level_id,
            'user_id' => $request->user_id,
       'pickup_station_id' => $request->pickup_station_id,
'dropoff_station_id' => $request->dropoff_station_id,

        ]);
        ActivityLog::create([
'user_id' => $request->user_id,
    'action' => 'create_student',
    'record_id' => $student->id,
    'description' => 'تم إنشاء طالب جديد',
]);


        // ربط الأيام
        $student->days()->sync($request->days ?? []);

        return response()->json([
            'status' => true,
            'message' => 'Student created successfully',
            'data' => $student->load([
                'university',
                'college',
                'department',
                'level',
                'days'
            ])
        ], 201);
    }

    // تحديث طالب
 public function update(Request $request, $id)
{
    $student = Student::findOrFail($id);

    $request->validate([
        'user_id' => 'sometimes|exists:users,id',
        'university_number' => 'sometimes|unique:students,university_number,' . $id,
        'city' => 'nullable',
        'gender' => 'nullable',
        'state' => 'nullable',

        'university_id' => 'sometimes|exists:universities,id',
        'college_id' => 'sometimes|exists:colleges,id',
        'department_id' => 'sometimes|exists:departments,id',
        'level_id' => 'sometimes|exists:levels,id',

        'days' => 'nullable|array',
        'days.*' => 'exists:days,id',

        'pickup_station_id' => 'nullable|exists:stations,id',
        'dropoff_station_id' => 'nullable|exists:stations,id',

        // 🔥 بيانات اليوزر
        'username' => 'sometimes|string|unique:users,username,' . $student->user_id,
        'full_name' => 'sometimes|string',
        'phone' => 'sometimes|string',
    ]);

    // ✅ تحديث بيانات الطالب
    $student->update([
        'university_number' => $request->university_number ?? $student->university_number,
        'city' => $request->city ?? $student->city,
        'gender' => $request->gender ?? $student->gender,
        'state' => $request->state ?? $student->state,
        'university_id' => $request->university_id ?? $student->university_id,
        'college_id' => $request->college_id ?? $student->college_id,
        'department_id' => $request->department_id ?? $student->department_id,
        'level_id' => $request->level_id ?? $student->level_id,
        'user_id' => $request->user_id ?? $student->user_id,
        'pickup_station_id' => $request->pickup_station_id ?? $student->pickup_station_id,
        'dropoff_station_id' => $request->dropoff_station_id ?? $student->dropoff_station_id,
    ]);
ActivityLog::create([
'user_id' => $request->user_id,
    'action' => 'update_student',
    'record_id' => $student->id,
    'description' => 'تم تعديل بيانات الطالب',
]);
    // 🔥 تحديث بيانات user المرتبط
    if ($student->user) {
        $student->user->update([
            'full_name' => $request->full_name ?? $student->user->full_name,
            'phone' => $request->phone ?? $student->user->phone,
                'username' => $request->username ?? $student->user->username,

        ]);
    }

    // تحديث الأيام إذا تم إرسالها
    if ($request->has('days')) {
        $student->days()->sync($request->days);
    }

  return response()->json([
    'message' => 'Student updated successfully',

    'student' => [
        'id' => $student->id,
        'university_number' => $student->university_number,
        'city' => $student->city,

        'gender' => in_array($student->gender, ['رجل', 'ذكر']) ? 'ذكر' : 'أنثى',

        'state' => strtolower($student->state) === 'active' ? 'active' : 'inactive',

        'user_id' => $student->user_id,
        'university_id' => $student->university_id,
        'college_id' => $student->college_id,
        'department_id' => $student->department_id,
        'level_id' => $student->level_id,

        'pickup_station_id' => $student->pickup_station_id,
        'dropoff_station_id' => $student->dropoff_station_id,

        'days' => $student->days->pluck('id')->toArray(),

     
    ],

    'user' => [
        'id' => optional($student->user)->id,
        'full_name' => optional($student->user)->full_name,
        'phone' => optional($student->user)->phone,
        'username' => optional($student->user)->username,
    ]
]);
}

    // حذف طالب
    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Student not found'
            ], 404);
        }

        $student->delete();

 ActivityLog::create([
        'user_id' => $request->user_id,
        'action' => 'delete_student',
        'record_id' => $student->id,
        'description' => 'تم حذف الطالب',
    ]);
        return response()->json([
            'status' => true,
            'message' => 'Student deleted successfully'
        ], 200);
    }



public function getStudentTrips(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id'
    ]);

    $student = Student::with('trips')->find($request->student_id);

    $data = $student->trips->map(function ($trip) {
        return [
            'status' => $trip->pivot->status,
            'trip_date' => $trip->trip_date, // 👈 الصح
        ];
    });

    return response()->json($data);
}
}
