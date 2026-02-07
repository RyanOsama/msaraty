<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    /**
     * عرض جميع المحطات
     */
    public function index()
    {
        $stations = Station::select(
                'id',
                'station_name',
                'location_x',
                'location_y',
                'description',
                'created_at'
            )
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'stations' => $stations
        ], 200);
    }

    /**
     * إضافة محطة
     */
    public function store(Request $request)
    {
        $request->validate([
            'station_name'        => 'required|string|max:255',
            'location_x'  => 'required|numeric',
            'location_y'  => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $station = Station::create([
            'station_name'        => $request->station_name,
            'location_x'  => $request->location_x,
            'location_y'  => $request->location_y,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'تم إضافة المحطة بنجاح',
            'station' => $station,
        ], 201);
    }

    /**
     * تعديل محطة
     */
    public function update(Request $request, $id)
    {
        $station = Station::find($id);

        if (!$station) {
            return response()->json([
                'message' => 'المحطة غير موجودة'
            ], 404);
        }

        $request->validate([
            'station_name'        => 'required|string|max:255',
            'location_x'  => 'required|numeric',
            'location_y'  => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $station->update([
            'station_name'        => $request->station_name,
            'location_x'  => $request->location_x,
            'location_y'  => $request->location_y,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'تم تعديل المحطة بنجاح',
            'station' => $station,
        ], 200);
    }

    /**
     * حذف محطة
     */
    public function destroy($id)
    {
        $station = Station::find($id);

        if (!$station) {
            return response()->json([
                'message' => 'المحطة غير موجودة'
            ], 404);
        }

        $station->delete();

        return response()->json([
            'message' => 'تم حذف المحطة بنجاح'
        ], 200);
    }
}
