<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    // عرض جميع السائقين
    public function index()
    {
        $drivers = Driver::all();

        return response()->json($drivers, 200);
    }

    // إضافة سائق
    public function store(Request $request)
    {
        $request->validate([
            'name_driver' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'state' => ['required', 'string', 'max:50'],
            'user_id' => ['required', 'integer'],
        ]);

        $driver = Driver::create([
            'name_driver' => $request->name_driver,
            'phone' => $request->phone,
            'state' => $request->state,
            'user_id' => $request->user_id,
        ]);

        return response()->json($driver, 201);
    }

    // تعديل سائق
   public function update(Request $request, Driver $driver)
{
    $request->validate([
        'name_driver' => ['sometimes', 'string', 'max:255'],
        'phone' => ['sometimes', 'string', 'max:20'],
        'state' => ['sometimes', 'string', 'max:50'],
        'user_id' => ['sometimes', 'integer'],
    ]);

    $driver->update($request->only([
        'name_driver',
        'phone',
        'state',
        'user_id'
    ]));

    return response()->json([
        'message' => 'تم تعديل السائق بنجاح',
        'driver' => $driver,
    ]);
}

    // حذف سائق
    public function destroy(Driver $driver)
    {
        $driver->delete();

        return response()->json([
            'message' => 'تم حذف السائق بنجاح',
        ]);
    }
}