<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\College;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    // عرض جميع الكليات مع الجامعة
    public function index()
    {
        $colleges = College::with('university')->get();

        return response()->json([
            'status' => true,
            'message' => 'Colleges retrieved successfully',
            'data' => $colleges
        ], 200);
    }

   
    public function show($id)
    {
        $college = College::with('university')->find($id);

        if (!$college) {
            return response()->json([
                'status' => false,
                'message' => 'College not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $college
        ], 200);
    }

    // إنشاء كلية
    public function store(Request $request)
    {
        $request->validate([
            'college_name' => 'required',
            'university_id' => 'required|exists:universities,id'
        ]);

        $college = College::create([
            'college_name' => $request->college_name,
            'university_id' => $request->university_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'College created successfully',
            'data' => $college
        ], 201);
    }

    // تحديث كلية
    public function update(Request $request, $id)
    {
        $college = College::find($id);

        if (!$college) {
            return response()->json([
                'status' => false,
                'message' => 'College not found'
            ], 404);
        }

        $request->validate([
            'college_name' => 'required',
            'university_id' => 'required|exists:universities,id'
        ]);

        $college->update([
            'college_name' => $request->college_name,
            'university_id' => $request->university_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'College updated successfully',
            'data' => $college
        ], 200);
    }

    // حذف كلية
    public function destroy($id)
    {
        $college = College::find($id);

        if (!$college) {
            return response()->json([
                'status' => false,
                'message' => 'College not found'
            ], 404);
        }

        $college->delete();

        return response()->json([
            'status' => true,
            'message' => 'College deleted successfully'
        ], 200);
    }
}
