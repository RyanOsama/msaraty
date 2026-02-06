<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RouteStation;
use Illuminate\Http\Request;

class RouteStationController extends Controller
{
    /**
     * ربط خط بعدة محطات
     */
    public function store(Request $request)
    {
        $request->validate([
            'route_id'      => 'required|exists:routes,id',
            'station_ids'   => 'required|array',
            'station_ids.*' => 'exists:stations,id',
        ]);

        foreach ($request->station_ids as $stationId) {
            RouteStation::updateOrCreate(
                [
                    'route_id'   => $request->route_id,
                    'station_id'=> $stationId,
                ]
            );
        }

        return back()->with('success', 'تم ربط الخط بالمحطات بنجاح');
    }

    /**
     * حذف ربط (خط ↔ محطة)
     */
   public function destroy(Request $request)
{
    $request->validate([
        'route_id'   => 'required|exists:routes,id',
        'station_id'=> 'required|exists:stations,id',
    ]);

    RouteStation::where('route_id', $request->route_id)
        ->where('station_id', $request->station_id)
        ->delete();

    return back()->with('success', 'تم حذف المحطة من الخط');
}


    public function update(Request $request)
{
    $request->validate([
        'route_id'     => 'required|exists:routes,id',
        'station_ids'  => 'required|array',
        'station_ids.*'=> 'exists:stations,id',
    ]);

    $route = \App\Models\Route::findOrFail($request->route_id);

    // حذف كل الربط القديم
    $route->stations()->sync($request->station_ids);

    return back()->with('success', 'تم تحديث محطات الخط بنجاح');
}

}
