<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    // عرض جميع السائقين
    public function index()
    {
        $drivers = Driver::with('bus')->get();
        return view('drivers.index', compact('drivers'));
    }

    // إضافة سائق
    public function store(Request $request)
    {
        $request->validate([
            'name_driver' => 'required',
            'phone' => 'nullable',
            'state' => 'nullable',
        ]);

        Driver::create([
            'name_driver' => $request->name_driver,
            'phone'       => $request->phone,
            'state'       => $request->state,
            'user_id'     => auth()->id(), // المستخدم الحالي
        ]);

        return back()->with('success', 'تم إضافة السائق');
    }

    // تحديث سائق
    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'name_driver' => 'required',
            'phone' => 'nullable',
            'state' => 'nullable',
        ]);

        $driver->update([
            'name_driver' => $request->name_driver,
            'phone'       => $request->phone,
            'state'       => $request->state,
        ]);

        return back()->with('success', 'تم تحديث بيانات السائق');
    }

    // حذف سائق
    public function destroy(Driver $driver)
    {
        // حماية: لو مرتبط بباص لا ينحذف
        if ($driver->bus) {
            return back()->with('error', 'لا يمكن حذف السائق لأنه مرتبط بباص');
        }

        $driver->delete();

        return back()->with('success', 'تم حذف السائق');
    }
}
