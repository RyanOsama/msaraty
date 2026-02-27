<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\University;
use App\Models\College;
use App\Models\Department;
use App\Models\Level;
use App\Models\Day;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // عرض جميع الطلاب
    public function index()
    {
        $students = Student::with([
            'university',
            'college',
            'department',
            'level',
            'days'
        ])->get();

        return view('/', compact('students'));
    }

    // عرض فورم الإضافة
    public function create()
    {
        $universities = University::all();
        $colleges = College::all();
        $departments = Department::all();
        $levels = Level::all();
        $days = Day::all();

        return view('students.create', compact(
            'universities',
            'colleges',
            'departments',
            'levels',
            'days'
        ));
    }

    // تخزين طالب جديد
  public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'university_number' => 'required|unique:students',
        'phone' => 'nullable',
        'city' => 'nullable',
        'gender' => 'nullable',
        'state' => 'nullable',
        'university_id' => 'required',
        'college_id' => 'required',
        'department_id' => 'required',
        'level_id' => 'required',
           'pickup_station_id' => 'required|different:dropoff_station_id',
        'dropoff_station_id' => 'required',
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
      'user_id' => auth()->id(), // ✅ ياخذ المستخدم الحالي
    ]);

    $student->days()->sync($request->days ?? []);

$student->stations()->attach([
        $request->pickup_station_id => ['type' => 'pickup'],
        $request->dropoff_station_id => ['type' => 'dropoff'],
    ]);
    return redirect()->back()
                     ->with('success', 'Student created successfully');
}


    // عرض طالب واحد
    public function show(Student $student)
    {
        $student->load(['university','college','department','level','days']);
        return view('students.show', compact('student'));
    }

    // عرض فورم التعديل
    public function edit(Student $student)
    {
        $universities = University::all();
        $colleges = College::all();
        $departments = Department::all();
        $levels = Level::all();
        $days = Day::all();

        return view('students.edit', compact(
            'student',
            'universities',
            'colleges',
            'departments',
            'levels',
            'days'
        ));
    }

    // تحديث البيانات
    public function update(Request $request, Student $student)
{
    $request->validate([
        'name' => 'required',
        'university_number' => 'required|unique:students,university_number,' . $student->id,
    ]);

    $student->update([
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
    ]);
// محطة الصعود
if ($request->pickup_station_id) {
    $student->stations()
        ->wherePivot('type', 'pickup')
        ->detach();

    $student->stations()->attach(
        $request->pickup_station_id,
        ['type' => 'pickup']
    );
}

// محطة النزول
if ($request->dropoff_station_id) {
    $student->stations()
        ->wherePivot('type', 'dropoff')
        ->detach();

    $student->stations()->attach(
        $request->dropoff_station_id,
        ['type' => 'dropoff']
    );
}

    $student->days()->sync($request->days ?? []);

    return redirect()->back()
                     ->with('success', 'Student updated successfully');
}


    // حذف الطالب
   public function destroy(Student $student)
{
    // فك علاقة المحطات
    $student->stations()->detach();

    // فك علاقة الأيام
    $student->days()->detach();

    // الآن نحذف الطالب
    $student->delete();

    return redirect()->back()
        ->with('success', 'تم حذف الطالب بنجاح');
}

}
