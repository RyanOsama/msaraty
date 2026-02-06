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

        return response()->json([
            'routes' => $routes
        ]);
    }

    // إضافة خط
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $route = Route::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'تم إضافة الخط بنجاح',
            'route'   => $route,
        ], 201);
    }

    // تعديل خط
    public function update(Request $request, Route $route)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $route->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'تم تعديل الخط بنجاح',
            'route'   => $route,
        ]);
    }

    // حذف خط
    public function destroy(Route $route)
    {
        $route->delete();

        return response()->json([
            'message' => 'تم حذف الخط بنجاح',
        ]);
    }
}
