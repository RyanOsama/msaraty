<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RouteStation;
use App\Models\Route;
use App\Models\station;


use Illuminate\Http\Request;
class RouteStationController extends Controller
{

    public function index()
{
    $routes = Route::with(['stations' => function ($q) {
        $q->orderBy('route_station.order');
    }])->get();

    $assign = $routes->map(function ($route) {
        return [
            'route_id' => $route->id,
            'stations' => $route->stations
                ->sortBy('pivot.order')
                ->pluck('id')
                ->values()
                ->toArray(),
            'id' => $route->id
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

    // تعديل ترتيب محطة واحدة
    public function updateOrder(Request $request)
    {
        $request->validate([
            'route_id'    => 'required|exists:routes,id',
            'station_id' => 'required|exists:stations,id',
            'order'      => 'required|integer|min:1',
        ]);

        RouteStation::where('route_id', $request->route_id)
            ->where('station_id', $request->station_id)
            ->update([
                'order' => $request->order
            ]);

        return response()->json([
            'status'  => true,
            'message' => 'تم تعديل ترتيب المحطة',
        ]);
    }

    // حذف محطة من خط
    public function destroy(Request $request)
    {
        $request->validate([
            'route_id'    => 'required|exists:routes,id',
            'station_id' => 'required|exists:stations,id',
        ]);

        RouteStation::where('route_id', $request->route_id)
            ->where('station_id', $request->station_id)
            ->delete();

        return response()->json([
            'status'  => true,
            'message' => 'تم حذف المحطة من الخط',
        ]);
    }

   
}
