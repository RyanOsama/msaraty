<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    // عرض جميع الخطوط
    public function index()
    {
        $routes = Route::all();

      
        return response()->json(
        $routes
        , 200);
    }

  public function store(Request $request)
{
    $request->validate([
        'route_name' => ['required', 'string', 'max:255'],
    ]);

    $route = Route::create([
        'route_name' => $request->route_name,
    ]);
    // تسجيل العملية في سجل النظام
    \App\Models\ActivityLog::create([
        'user_id'     => request()->user_id ?? auth()->id(),
        'action'      => 'تم إنشاء مسار جديد',
        'record_id'   => $route->id,
        'description' => 'تم إنشاء مسار جديد باسم: ' . $route->route_name,
    ]);

    return response()->json($route, 201);
}

    // تعديل خط
    public function update(Request $request, Route $route)
    {
        $request->validate([
            'route_name' => ['required', 'string', 'max:255'],
        ]);

        $route->update([
            'route_name' => $request->route_name,
        ]);
        // تسجيل العملية في سجل النظام
        \App\Models\ActivityLog::create([
            'user_id'     => request()->user_id ?? auth()->id(),
            'action'      => 'تم تعديل المسار',
            'record_id'   => $route->id,
            'description' => 'تم تعديل اسم المسار إلى: ' . $route->route_name,
        ]);

        return response()->json([
            'message' => 'تم تعديل الخط بنجاح',
            'route'   => $route,
        ]);
    }

    // حذف خط
    public function destroy(Route $route)
    {
                // تسجيل العملية في سجل النظام
        \App\Models\ActivityLog::create([
            'user_id'     => request()->user_id ?? auth()->id(),
            'action'      => 'تم حذف المسار',
            'record_id'   => $route->id,
            'description' => 'تم حذف المسار: ' . $route->route_name,
        ]);

        $route->delete();

        return response()->json([
            'message' => 'تم حذف الخط بنجاح',
        ]);
    }
}
