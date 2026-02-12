<?php

namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    // عرض جميع الجامعات
    public function index()
    {
        $universities = University::all();
        return view('universities.index', compact('universities'));
    }

    // تخزين جامعة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'university_name' => 'required|unique:universities'
        ]);

        University::create([
            'university_name' => $request->university_name
        ]);

        return back()->with('success', 'تم إضافة الجامعة');
    }

    // تحديث جامعة
    public function update(Request $request, University $university)
    {
        $request->validate([
            'university_name' => 'required|unique:universities,university_name,' . $university->id
        ]);

        $university->update([
            'university_name' => $request->university_name
        ]);

        return back()->with('success', 'تم تحديث الجامعة');
    }

    // حذف جامعة
    public function destroy(University $university)
    {
        $university->delete();

        return back()->with('success', 'تم حذف الجامعة');
    }
}
