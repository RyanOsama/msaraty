<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RouteStation;
use Illuminate\Http\Request;
use App\Models\Route;


class RouteStationController extends Controller
{
    /**
     * عرض جميع الربط (محطة ↔ خط)
     */
    public function index()
    {
        $data = RouteStation::with(['station:id,name', 'route:id,name'])
            ->orderBy('station_id')
            ->orderBy('order')
            ->get()
            ->map(function ($item) {
                return [
                    'id'           => $item->id,
                    'station_id'   => $item->station_id,
                    'station_name' => $item->station->name ?? null,
                    'route_id'     => $item->route_id,
                    'route_name'   => $item->route->name ?? null,
                    'order'        => $item->order,
                ];
            });

        return response()->json([
            'data' => $data
        ], 200);
    }

    /**
     * ربط محطة مع أكثر من خط
     */
    public function store(Request $request)
    {
        $request->validate([
            'station_id' => 'required|exists:stations,id',
            'route_ids'  => 'required|array',
            'route_ids.*'=> 'exists:routes,id',
            'order'      => 'required|integer',
        ]);

        foreach ($request->route_ids as $routeId) {
            RouteStation::updateOrCreate(
                [
                    'station_id' => $request->station_id,
                    'route_id'   => $routeId,
                ],
                [
                    'order' => $request->order,
                ]
            );
        }

        return response()->json([
            'message' => 'تم ربط المحطة بالخطوط بنجاح'
        ], 201);
    }

    /**
     * تعديل ترتيب محطة داخل خط
     */
    public function update(Request $request, $routeId)
{
    $request->validate([
        'station_ids'   => 'required|array',
        'station_ids.*' => 'exists:stations,id',
    ]);

    $route = Route::find($routeId);

    if (!$route) {
        return response()->json([
            'message' => 'الخط غير موجود'
        ], 404);
    }

    // sync = حذف القديم + إضافة الجديد
    $route->stations()->sync($request->station_ids);

    return response()->json([
        'message' => 'تم تحديث محطات الخط بنجاح',
        'route_id' => $route->id,
        'stations' => $route->stations()->select('stations.id', 'stations.name')->get()
    ], 200);
}

    /**
     * حذف ربط محطة مع خط
     */
    public function destroy($id)
    {
        $routeStation = RouteStation::find($id);

        if (!$routeStation) {
            return response()->json([
                'message' => 'الربط غير موجود'
            ], 404);
        }

        $routeStation->delete();

        return response()->json([
            'message' => 'تم حذف الربط بنجاح'
        ], 200);
    }
}
