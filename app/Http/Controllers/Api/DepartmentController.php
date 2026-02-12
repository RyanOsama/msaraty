<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // عرض جميع الأقسام مع الكلية
    public function index()
    {
        $departments = Department::with('college')->get();

        return response()->json([
            'status' => true,
            'message' => 'Departments retrieved successfully',
            'data' => $departments
        ], 200);
    }

    // عرض قسم واحد
    public function show($id)
    {
        $department = Department::with('college')->find($id);

        if (!$department) {
            return response()->json([
                'status' => false,
                'message' => 'Department not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $department
        ], 200);
    }

    // إنشاء قسم
    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required',
            'college_id' => 'required|exists:colleges,id'
        ]);

        $department = Department::create([
            'department_name' => $request->department_name,
            'college_id' => $request->college_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Department created successfully',
            'data' => $department
        ], 201);
    }

    // تحديث قسم
    public function update(Request $request, $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'status' => false,
                'message' => 'Department not found'
            ], 404);
        }

        $request->validate([
            'department_name' => 'required',
            'college_id' => 'required|exists:colleges,id'
        ]);

        $department->update([
            'department_name' => $request->department_name,
            'college_id' => $request->college_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Department updated successfully',
            'data' => $department
        ], 200);
    }

    // حذف قسم
    public function destroy($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'status' => false,
                'message' => 'Department not found'
            ], 404);
        }

        $department->delete();

        return response()->json([
            'status' => true,
            'message' => 'Department deleted successfully'
        ], 200);
    }
}
