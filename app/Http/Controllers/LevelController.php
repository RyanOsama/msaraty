<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Department;
use Illuminate\Http\Request;
class LevelController extends Controller
{
    public function index()
    {
        $levels = Level::with('departments.college.university')->get();
        return view('levels.index', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_name' => 'required',
            'department_ids' => 'required|array'
        ]);

        // إنشاء المستوى
        $level = Level::create([
            'level_name' => $request->level_name
        ]);

        // ربطه بالأقسام
        $level->departments()->sync($request->department_ids);

        return back()->with('success', 'تم إضافة المستوى');
    }

    public function update(Request $request, Level $level)
    {
        $request->validate([
            'level_name' => 'required',
            'department_ids' => 'required|array'
        ]);

        $level->update([
            'level_name' => $request->level_name
        ]);

        // تحديث الربط
        $level->departments()->sync($request->department_ids);

        return back()->with('success', 'تم تحديث المستوى');
    }

    public function destroy(Level $level)
    {
        $level->delete();
        return back()->with('success', 'تم حذف المستوى');
    }
public function levelsByDepartment($id)
{
    $department = \App\Models\Department::find($id);

    if (!$department) {
        return response()->json([]);
    }

    return response()->json(
        $department->levels()
            ->select('levels.id','levels.level_name')
            ->get()
    );
}


}
