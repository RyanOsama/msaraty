<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Department;
use Illuminate\Http\Request;
   use Illuminate\Database\QueryException;


class LevelController extends Controller
{
    // عرض جميع المستويات مع الأقسام والكلية والجامعة
public function index()
{
    $levels = Level::with('departments')->get()->map(function ($level) {

        return [
            'id' => $level->id,
            'level_name' => $level->level_name,
            'department_id' => $level->departments->first()?->id
        ];
    });

    return response()->json($levels);
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
        'department_id' => 'required|exists:departments,id'
    ]);

    $level = Level::create([
        'level_name' => $request->level_name
    ]);

    // ربط القسم بالمستوى
    $level->departments()->attach($request->department_id);

    return response()->json([
        'status' => true,
        'message' => 'Level created successfully',
        'data' => $level
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
    try {
        $level = Level::findOrFail($id);
        $level->delete();

        return response()->json([
            'status' => true,
            'message' => 'تم حذف المستوى بنجاح'
        ], 200);

    } catch (QueryException $e) {

        return response()->json([
            'status' => false,
            'message' => 'لا يمكن حذف المستوى لأنه مرتبط بطلاب'
        ], 409);
    }
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
