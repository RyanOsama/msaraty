<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
  

//     public function index()
// {
//     $students = Student::with([
//         'university',
//         'college',
//         'department',
//         'level',
//         'days',
//         'stations'
//     ])->get();

//     return response()->json($students, 200);
// }





// public function index()
// {
//     $students = Student::with([
//         'university',
//         'college',
//         'department',
//         'level',
//         'pickupStation',
// 'dropoffStation'
//     ])->get();

//     $result = $students->map(function ($student) {

//         // محطة الصعود
//         $pickup = $student->stations
//             ->where('pivot.type', 'pickup')
//             ->first();

//         // محطة النزول
//         $dropoff = $student->stations
//             ->where('pivot.type', 'dropoff')
//             ->first();

//         return [
//             'id' => $student->id,
//             'name' => $student->name,
//             'phone' => $student->phone,
//             'university_number' => $student->university_number,
//             'city' => $student->city,

//             // ✅ توحيد الجنس
//             'gender' => in_array($student->gender, ['رجل', 'ذكر']) ? 'ذكر' : 'أنثى',

//             // ✅ توحيد الحالة (important)
//             'state' => strtolower($student->state) === 'active' ? 'active' : 'inactive',

//             'user_id' => $student->user_id,
//             'university_id' => $student->university_id,
//             'college_id' => $student->college_id,
//             'department_id' => $student->department_id,
//             'level_id' => $student->level_id,

//             // ✅ الحقول اللي الفرونت يحتاجها
//             'pickup_station_id' => $pickup?->id,
//             'dropoff_station_id' => $dropoff?->id,

//             'created_at' => $student->created_at,
//             'updated_at' => $student->updated_at,
//         ];
//     });

//     return response()->json($result, 200);
// }

public function index()
{
    $students = Student::with([
        'university',
        'college',
        'department',
        'level',
        'pickupStation',
        'dropoffStation'
    ])->get();

    $result = $students->map(function ($student) {

        return [
            'id' => $student->id,
            'name' => $student->name,
            'phone' => $student->phone,
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

            'created_at' => $student->created_at,
            'updated_at' => $student->updated_at,
        ];
    });

    return response()->json($result, 200);
}



















    
    // إنشاء طالب
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required',
            'university_number' => 'required|unique:students',
            'phone' => 'nullable',
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
            'name' => $request->name,
            'phone' => $request->phone,
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
        'name' => 'sometimes',
        'university_number' => 'sometimes|unique:students,university_number,' . $id,
        'phone' => 'nullable',
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
    ]);

    $student->update([
        'name' => $request->name ?? $student->name,
        'phone' => $request->phone ?? $student->phone,
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

    // تحديث الأيام إذا تم إرسالها
    if ($request->has('days')) {
        $student->days()->sync($request->days);
    }

    return response()->json([
        'status' => true,
        'message' => 'Student updated successfully',
        'data' => $student->load([
            'university',
            'college',
            'department',
            'level',
            'days'
        ])
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

        return response()->json([
            'status' => true,
            'message' => 'Student deleted successfully'
        ], 200);
    }
}
