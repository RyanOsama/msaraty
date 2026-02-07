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
        $data = RouteStation::with([
                'station:id,station_name',
                'route:id,route_name'
            ])
            ->orderBy('station_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id'            => $item->id,
                    'station_id'    => $item->station_id,
                    'station_name'  => $item->station->station_name ?? null,
                    'route_id'      => $item->route_id,
                    'route_name'    => $item->route->route_name ?? null,
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
        'route_id'      => 'required|exists:routes,id',
        'station_ids'   => 'required|array|min:1',
        'station_ids.*' => 'exists:stations,id',
    ]);

    foreach ($request->station_ids as $stationId) {
        RouteStation::firstOrCreate([
            'route_id'   => $request->route_id,
            'station_id' => $stationId,
        ]);
    }

    return response()->json([
        'message'   => 'تم ربط الخط بالمحطات بنجاح',
        'route_id'  => $request->route_id,
        'stations'  => $request->station_ids
    ], 201);
}


    /**
     * تحديث محطات خط (sync)
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

        $route->stations()->sync($request->station_ids);

        return response()->json([
            'message'   => 'تم تحديث محطات الخط بنجاح',
            'route_id'  => $route->id,
            'stations'  => $route->stations()
                                ->select('stations.id', 'stations.station_name')
                                ->get()
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
