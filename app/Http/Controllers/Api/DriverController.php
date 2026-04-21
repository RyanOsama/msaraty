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
    $drivers = Driver::with('user')->get();

    $result = $drivers->map(function ($driver) {
        return [
            'id' => $driver->id,

            'name_driver' => $driver->user->full_name ?? null, // 👈 من users
            'phone' => $driver->user->phone ?? null,

            'state' => $driver->state,
            'user_id' => $driver->user_id,

            'created_at' => $driver->created_at,
            'updated_at' => $driver->updated_at,
        ];
    });

    return response()->json($result, 200);
}

    // إضافة سائق
    public function store(Request $request)
    {
        $request->validate([
            'state' => ['required', 'string', 'max:50'],
            'user_id' => ['required', 'integer'],
        ]);

        $driver = Driver::create([
            'state' => $request->state,
            'user_id' => $request->user_id,
        ]);

        return response()->json($driver, 201);
    }

    // تعديل سائق
   public function update(Request $request, Driver $driver)
{
    $request->validate([
        
        'state' => ['sometimes', 'string', 'max:50'],
        'user_id' => ['sometimes', 'integer'],
    ]);

    $driver->update($request->only([
   
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