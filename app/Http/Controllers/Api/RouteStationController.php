<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RouteStation;
use App\Models\Route;
use App\Models\station;
use App\Models\RouteStationOrder;



use Illuminate\Http\Request;
class RouteStationController extends Controller
{

 public function index()
{
    $routes = Route::with(['stations' => function ($q) {
        $q->orderBy('route_station.order');
    }])->get();

    $assign = $routes->map(function ($route) {

        // 🔥 نجيب assign_id الحقيقي من route_station
        $assignId = \DB::table('route_station')
            ->where('route_id', $route->id)
            ->value('id');

        return [
            'id' => $assignId, // ✅ assign_id الصحيح
            'route_id' => $route->id,

            'stations' => $route->stations
                ->sortBy('pivot.order')
                ->pluck('id')
                ->values()
                ->toArray(),
        ];
    });

    return response()->json($assign);
}











   
  public function store(Request $request)
{
    $request->validate([
        'route_id' => 'required|exists:routes,id',
        'stations' => 'required|array',
        'stations.*' => 'required|exists:stations,id',
    ]);

    foreach ($request->stations as $index => $stationId) {

        RouteStation::updateOrCreate(
            [
                'route_id'   => $request->route_id,
                'station_id' => $stationId,
            ],
            [
                'order' => $index + 1,
            ]
        );
    }

    return response()->json([
        'status'  => true,
        'message' => 'تمت إضافة / تحديث المحطات بنجاح',
    ], 200);
}

public function updateOrder(Request $request, $routeId)
{
    $request->validate([
        'stations' => 'required|array',
        'stations.*' => 'exists:stations,id'
    ]);

    $stations = $request->stations;

    // حذف المحطات التي أزيلت
    RouteStation::where('route_id', $routeId)
        ->whereNotIn('station_id', $stations)
        ->delete();

    // تحديث أو إضافة المحطات
    foreach ($stations as $index => $stationId) {

        RouteStation::updateOrCreate(
            [
                'route_id' => $routeId,
                'station_id' => $stationId
            ],
            [
                'order' => $index + 1
            ]
        );
    }

    return response()->json([
        'status' => true,
        'message' => 'تم تحديث المحطات بنجاح'
    ]);
}
    // 
public function destroy($route_id)
{
    $deleted = RouteStation::where('route_id', $route_id)->delete();

    if (!$deleted) {
        return response()->json([
            'status' => false,
            'message' => 'لا توجد محطات مرتبطة بهذا المسار'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'message' => 'تم حذف جميع محطات المسار'
    ]);
}
   
   
}
