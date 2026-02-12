<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Department;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    // عرض جميع المستويات مع الأقسام والكلية والجامعة
    public function index()
    {
        $levels = Level::with('departments.college.university')->get();

        return response()->json([
            'status' => true,
            'message' => 'Levels retrieved successfully',
            'data' => $levels
        ], 200);
    }

    // عرض مستوى واحد
    public function show($id)
    {
        $level = Level::with('departments.college.university')->find($id);

        if (!$level) {
            return response()->json([
                'status' => false,
                'message' => 'Level not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $level
        ], 200);
    }

    // إنشاء مستوى
    public function store(Request $request)
    {
        $request->validate([
            'level_name' => 'required',
            'department_ids' => 'required|array',
            'department_ids.*' => 'exists:departments,id'
        ]);

        $level = Level::create([
            'level_name' => $request->level_name
        ]);

        // ربط الأقسام
        $level->departments()->sync($request->department_ids);

        return response()->json([
            'status' => true,
            'message' => 'Level created successfully',
            'data' => $level->load('departments')
        ], 201);
    }

    // تحديث مستوى
    public function update(Request $request, $id)
    {
        $level = Level::find($id);

        if (!$level) {
            return response()->json([
                'status' => false,
                'message' => 'Level not found'
            ], 404);
        }

        $request->validate([
            'level_name' => 'required',
            'department_ids' => 'required|array',
            'department_ids.*' => 'exists:departments,id'
        ]);

        $level->update([
            'level_name' => $request->level_name
        ]);

        $level->departments()->sync($request->department_ids);

        return response()->json([
            'status' => true,
            'message' => 'Level updated successfully',
            'data' => $level->load('departments')
        ], 200);
    }

    // حذف مستوى
    public function destroy($id)
    {
        $level = Level::find($id);

        if (!$level) {
            return response()->json([
                'status' => false,
                'message' => 'Level not found'
            ], 404);
        }

        $level->delete();

        return response()->json([
            'status' => true,
            'message' => 'Level deleted successfully'
        ], 200);
    }

    // عرض المستويات حسب القسم
    public function levelsByDepartment($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'status' => false,
                'message' => 'Department not found',
                'data' => []
            ], 404);
        }

        $levels = $department->levels()
            ->select('levels.id','levels.level_name')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $levels
        ], 200);
    }
}
