<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    // عرض جميع الجامعات
    public function index()
    {
        $universities = University::all();

        return response()->json([
            'status' => true,
            'message' => 'Universities retrieved successfully',
            'data' => $universities
        ], 200);
    }

    // عرض جامعة واحدة
    public function show($id)
    {
        $university = University::find($id);

        if (!$university) {
            return response()->json([
                'status' => false,
                'message' => 'University not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $university
        ], 200);
    }

    // تخزين جامعة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'university_name' => 'required|unique:universities'
        ]);

        $university = University::create([
            'university_name' => $request->university_name
        ]);

        return response()->json([
            'status' => true,
            'message' => 'University created successfully',
            'data' => $university
        ], 201);
    }

    // تحديث جامعة
    public function update(Request $request, $id)
    {
        $university = University::find($id);

        if (!$university) {
            return response()->json([
                'status' => false,
                'message' => 'University not found'
            ], 404);
        }

        $request->validate([
            'university_name' => 'required|unique:universities,university_name,' . $id
        ]);

        $university->update([
            'university_name' => $request->university_name
        ]);

        return response()->json([
            'status' => true,
            'message' => 'University updated successfully',
            'data' => $university
        ], 200);
    }

    // حذف جامعة
    public function destroy($id)
    {
        $university = University::find($id);

        if (!$university) {
            return response()->json([
                'status' => false,
                'message' => 'University not found'
            ], 404);
        }

        $university->delete();

        return response()->json([
            'status' => true,
            'message' => 'University deleted successfully'
        ], 200);
    }
}
