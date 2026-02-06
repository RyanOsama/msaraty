<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index()
    {
        $stations = Station::all();
        return view('welcome', compact('stations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location_x' => 'required|numeric',
            'location_y' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        Station::create($request->only([
            'name',
            'location_x',
            'location_y',
            'description',
        ]));

        return redirect()->back()->with('success', 'تم إضافة المحطة');
    }

    public function update(Request $request, Station $station)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location_x' => 'required|numeric',
            'location_y' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $station->update($request->only([
            'name',
            'location_x',
            'location_y',
            'description',
        ]));

        return redirect()->back()->with('success', 'تم تعديل المحطة');
    }

    public function destroy(Station $station)
    {
        $station->delete();
        return redirect()->back()->with('success', 'تم حذف المحطة');
    }
}
