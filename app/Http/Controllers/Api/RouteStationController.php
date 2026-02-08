<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RouteStation;
use App\Models\Route;

use Illuminate\Http\Request;

class RouteStationController extends Controller
{


public function index()
{
    $routes = Route::with(['stations' => function ($q) {
        $q->orderBy('route_station.order');
    }])->get();

    return response()->json([
        'status' => true,
        'data'   => $routes
    ]);
}


    // إضافة محطة / محطات
    public function store(Request $request)
    {
        $request->validate([
            'route_id' => 'required|exists:routes,id',
            'stations' => 'required|array',
        ]);

        foreach ($request->stations as $station) {
            if (empty($station['station_id']) || empty($station['order'])) {
                continue;
            }

            RouteStation::updateOrCreate(
                [
                    'route_id'    => $request->route_id,
                    'station_id' => $station['station_id'],
                ],
                [
                    'order' => $station['order'],
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

    // حفظ الترتيب كامل دفعة واحدة
    public function bulkUpdateOrder(Request $request)
    {
        $request->validate([
            'route_id' => 'required|exists:routes,id',
            'orders'   => 'required|array',
        ]);

        foreach ($request->orders as $stationId => $order) {
            if (!$order) {
                continue;
            }

            RouteStation::where('route_id', $request->route_id)
                ->where('station_id', $stationId)
                ->update([
                    'order' => $order
                ]);
        }

        return response()->json([
            'status'  => true,
            'message' => 'تم حفظ ترتيب المحطات بالكامل',
        ]);
    }
}
