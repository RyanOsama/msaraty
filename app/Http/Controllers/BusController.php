<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Driver;
use Illuminate\Http\Request;

class BusController extends Controller
{
    // عرض جميع الباصات
    public function index()
    {
        $buses = Bus::with('driver')->get();
        return view('buses.index', compact('buses'));
    }

    // إضافة باص
    public function store(Request $request)
    {
        $request->validate([
            'number_passengers' => 'required|integer',
            'type_fuel' => 'nullable',
            'driver_id' => 'required|unique:buses,driver_id', // كل سائق له باص واحد
        ]);

        Bus::create([
            'number_passengers' => $request->number_passengers,
            'type_fuel' => $request->type_fuel,
            'driver_id' => $request->driver_id,
        ]);

        return back()->with('success', 'تم إضافة الباص');
    }

    // تعديل باص
    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'number_passengers' => 'required|integer',
            'type_fuel' => 'nullable',
            'driver_id' => 'required|unique:buses,driver_id,' . $bus->id,
        ]);

        $bus->update([
            'number_passengers' => $request->number_passengers,
            'type_fuel' => $request->type_fuel,
            'driver_id' => $request->driver_id,
        ]);

        return back()->with('success', 'تم تحديث بيانات الباص');
    }

    // حذف باص
    public function destroy(Bus $bus)
    {
        $bus->delete();
        return back()->with('success', 'تم حذف الباص');
    }
}
