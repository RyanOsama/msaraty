<?php

namespace App\Http\Controllers;

use App\Models\Day;
use Illuminate\Http\Request;

class DayController extends Controller
{
    // عرض جميع الأيام
    public function index()
    {
        $days = Day::all();
        return view('welcome', compact('days'));
    }

    // إضافة يوم
    public function store(Request $request)
    {
        $request->validate([
            'day_name' => 'required|unique:days,day_name'
        ]);

        Day::create([
            'day_name' => $request->day_name
        ]);

        return back()->with('success', 'تم إضافة اليوم');
    }

    // تعديل يوم
    public function update(Request $request, Day $day)
    {
        $request->validate([
            'day_name' => 'required|unique:days,day_name,' . $day->id
        ]);

        $day->update([
            'day_name' => $request->day_name
        ]);

        return back()->with('success', 'تم تعديل اليوم');
    }

    // حذف يوم
    public function destroy(Day $day)
    {
        $day->delete();

        return back()->with('success', 'تم حذف اليوم');
    }
}
