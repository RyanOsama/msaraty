<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    // عرض جميع الباصات
    public function index()
    {
        $buses = Bus::all();

        return response()->json($buses, 200);
    }

    // إضافة باص
    public function store(Request $request)
    {
        $request->validate([
                    'name' => ['required', 'string', 'max:255'], // ✅ اسم الباص

            'number_passengers' => ['required', 'integer'],
            'type_fuel' => ['required', 'string', 'max:50'],
            'driver_id' => ['required', 'integer'],
        ]);

        $bus = Bus::create([
                    'name' => $request->name, // ✅ أضف هذا السطر

            'number_passengers' => $request->number_passengers,
            'type_fuel' => $request->type_fuel,
            'driver_id' => $request->driver_id,
        ]);

        return response()->json($bus, 201);
    }

    // تعديل باص
  public function update(Request $request, Bus $bus)
{
    $request->validate([
                'name' => ['sometimes', 'string', 'max:255'], // ✅ اسم الباص

        'number_passengers' => ['sometimes', 'integer'],
        'type_fuel' => ['sometimes', 'string', 'max:50'],
        'driver_id' => ['sometimes', 'integer'],
    ]);

    $bus->update($request->only([
                'name', // ✅ أضف هذا

        'number_passengers',
        'type_fuel',
        'driver_id'
    ]));

    return response()->json([
        'message' => 'تم تعديل الباص بنجاح',
        'bus' => $bus,
    ]);
}

    // حذف باص
    public function destroy(Bus $bus)
    {
        $bus->delete();

        return response()->json([
            'message' => 'تم حذف الباص بنجاح',
        ]);
    }
}