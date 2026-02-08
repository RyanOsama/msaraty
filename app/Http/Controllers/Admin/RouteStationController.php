<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RouteStation;
use Illuminate\Http\Request;

class RouteStationController extends Controller
{
    // إضافة محطة/محطات (زيادة أو تعديل ترتيب)
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
                    'route_id'   => $request->route_id,
                    'station_id'=> $station['station_id'],
                ],
                [
                    'order' => $station['order'],
                ]
            );
        }

        return back()->with('success', 'تمت العملية بنجاح');
    }

    // تعديل ترتيب محطة
    public function updateOrder(Request $request)
    {
        $request->validate([
            'route_id'   => 'required|exists:routes,id',
            'station_id'=> 'required|exists:stations,id',
            'order'     => 'required|integer|min:1',
        ]);

        RouteStation::where('route_id', $request->route_id)
            ->where('station_id', $request->station_id)
            ->update([
                'order' => $request->order
            ]);

        return back()->with('success', 'تم تعديل الترتيب');
    }

    // حذف محطة من خط
    public function destroy(Request $request)
    {
        $request->validate([
            'route_id'   => 'required|exists:routes,id',
            'station_id'=> 'required|exists:stations,id',
        ]);

        RouteStation::where('route_id', $request->route_id)
            ->where('station_id', $request->station_id)
            ->delete();

        return back()->with('success', 'تم حذف المحطة');
    }
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

    return back()->with('success', 'تم حفظ ترتيب المحطات بالكامل');
}

}
