<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Day;
use Illuminate\Http\Request;

class DayController extends Controller
{
    // عرض جميع الأيام
    public function index()
    {
        $days = Day::all();

        return response()->json([
            'status' => true,
            'message' => 'Days retrieved successfully',
            'data' => $days
        ], 200);
    }

    // عرض يوم واحد
    public function show($id)
    {
        $day = Day::find($id);

        if (!$day) {
            return response()->json([
                'status' => false,
                'message' => 'Day not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $day
        ], 200);
    }

    // إنشاء يوم
    public function store(Request $request)
    {
        $request->validate([
            'day_name' => 'required|unique:days,day_name'
        ]);

        $day = Day::create([
            'day_name' => $request->day_name
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Day created successfully',
            'data' => $day
        ], 201);
    }

    // تحديث يوم
    public function update(Request $request, $id)
    {
        $day = Day::find($id);

        if (!$day) {
            return response()->json([
                'status' => false,
                'message' => 'Day not found'
            ], 404);
        }

        $request->validate([
            'day_name' => 'required|unique:days,day_name,' . $id
        ]);

        $day->update([
            'day_name' => $request->day_name
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Day updated successfully',
            'data' => $day
        ], 200);
    }

    // حذف يوم
    public function destroy($id)
    {
        $day = Day::find($id);

        if (!$day) {
            return response()->json([
                'status' => false,
                'message' => 'Day not found'
            ], 404);
        }

        $day->delete();

        return response()->json([
            'status' => true,
            'message' => 'Day deleted successfully'
        ], 200);
    }
}
