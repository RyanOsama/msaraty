<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\College;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('college')->get();
        return view('departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required',
            'college_id' => 'required'
        ]);

        Department::create([
            'department_name' => $request->department_name,
            'college_id' => $request->college_id
        ]);

        return back()->with('success', 'تم إضافة القسم');
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'department_name' => 'required',
            'college_id' => 'required'
        ]);

        $department->update([
            'department_name' => $request->department_name,
            'college_id' => $request->college_id
        ]);

        return back()->with('success', 'تم تحديث القسم');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return back()->with('success', 'تم حذف القسم');
    }
}
