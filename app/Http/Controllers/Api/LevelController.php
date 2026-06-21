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
            'department_id' => $level->departments->first()?->id,
            'department_ids' => $level->departments->pluck('id')->toArray()
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
        'department_ids' => 'required|array',
        'department_ids.*' => 'exists:departments,id'
    ]);

    $level = Level::create([
        'level_name' => $request->level_name
    ]);

    $level->departments()->sync($request->department_ids);
    // تسجيل العملية في سجل النظام
    \App\Models\ActivityLog::create([
        'user_id'     => request()->user_id ?? auth()->id(),
        'action'      => 'تم إنشاء مستوى جديد',
        'record_id'   => $level->id,
        'description' => 'تم إنشاء مستوى جديد باسم: ' . $level->level_name,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Level created successfully',
        'data' => [
            'id' => $level->id,
            'level_name' => $level->level_name,
            'department_ids' => $request->department_ids
        ]
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
    // تسجيل العملية في سجل النظام
    \App\Models\ActivityLog::create([
        'user_id'     => request()->user_id ?? auth()->id(),
        'action'      => 'تم تعديل مستوى',
        'record_id'   => $level->id,
        'description' => 'تم تعديل بيانات المستوى: ' . $level->level_name,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Level updated successfully',
        'data' => [
            'id' => $level->id,
            'level_name' => $level->level_name,
            'department_ids' => $request->department_ids
        ]
    ], 200);
}
    // حذف مستوى

public function destroy($id)
{
    try {
        $level = Level::findOrFail($id);
        $level->delete();
        // تسجيل العملية في سجل النظام
        \App\Models\ActivityLog::create([
            'user_id'     => request()->user_id ?? auth()->id(),
            'action'      => 'تم حذف مستوى',
            'record_id'   => $id,
            'description' => 'تم حذف المستوى: ' . $level->level_name,
        ]);

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
