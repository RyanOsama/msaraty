<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\University;
use Illuminate\Http\Request;
   use Illuminate\Database\QueryException;


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


public function destroy(College $college)
{
    try {
        $college->delete();
        return back()->with('success', 'تم حذف الكلية بنجاح');
    } catch (QueryException $e) {
        return back()->with('error', 'لا يمكن حذف الكلية لأنها مرتبطة بطلاب');
    }
}

}
