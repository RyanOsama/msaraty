<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\University;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    // عرض
    public function index()
    {
        $colleges = College::with('university')->get();
        return view('colleges.index', compact('colleges'));
    }

    // إضافة
    public function store(Request $request)
    {
        $request->validate([
            'college_name' => 'required',
            'university_id' => 'required'
        ]);

        College::create([
            'college_name' => $request->college_name,
            'university_id' => $request->university_id,
        ]);

        return back()->with('success', 'تم إضافة الكلية');
    }

    // تعديل
    public function update(Request $request, College $college)
    {
        $request->validate([
            'college_name' => 'required',
            'university_id' => 'required'
        ]);

        $college->update([
            'college_name' => $request->college_name,
            'university_id' => $request->university_id,
        ]);

        return back()->with('success', 'تم تحديث الكلية');
    }

    // حذف
    public function destroy(College $college)
    {
        $college->delete();
        return back()->with('success', 'تم حذف الكلية');
    }
}
