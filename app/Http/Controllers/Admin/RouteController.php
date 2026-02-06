<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    // عرض جميع الخطوط
    public function index()
    {
        $routes = Route::all();
        return view('admin.routes.index', compact('routes'));
    }

    // إضافة خط
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Route::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'تم إضافة الخط');
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

        return redirect()->back()->with('success', 'تم التعديل');
    }

    // حذف خط
    public function destroy(Route $route)
    {
        $route->delete();
        return redirect()->back()->with('success', 'تم الحذف');
    }
}
