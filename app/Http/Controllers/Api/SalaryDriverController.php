<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DriverSalary;

class SalaryDriverController extends Controller
{

    public function index()
    {
        return response()->json(
            DriverSalary::with('driver')
                ->latest()
                ->get()
        );
    }


    public function store(Request $request)
    {
        $request->validate([

            'driver_id' => 'required|exists:drivers,id',
            'amount' => 'required|numeric',
            'for_month' => 'required|string',
            'status' => 'required|in:paid,unpaid',

        ]);

        $salary = DriverSalary::create([

            'driver_id' => $request->driver_id,
            'amount' => $request->amount,
            'for_month' => $request->for_month,
            'status' => $request->status,

        ]);

        return response()->json([

            'message' => 'تم إضافة الراتب',
            'data' => $salary

        ], 201);
    }


    public function update(
        Request $request,
        $id
    )
    {
        $salary =
            DriverSalary::find($id);

        if (!$salary) {

            return response()->json([
                'message' => 'الراتب غير موجود'
            ], 404);
        }

        $request->validate([

            'driver_id' => 'sometimes|exists:drivers,id',
            'amount' => 'sometimes|numeric',
            'for_month' => 'sometimes|string',
            'status' => 'sometimes|in:paid,unpaid',

        ]);

        $salary->update(

            $request->only([

                'driver_id',
                'amount',
                'for_month',
                'status'

            ])

        );
        // تسجيل العملية في سجل النظام
        \App\Models\ActivityLog::create([
            'user_id'     => request()->user_id ?? auth()->id(),
            'action'      => 'تم تعديل راتب سائق',
            'record_id'   => $salary->id,
            'description' => 'تم تعديل راتب السائق لشهر: ' . $salary->for_month,
        ]);

        return response()->json([

            'message' => 'تم التعديل',

            'data' => $salary->fresh()

        ]);
    }


    public function destroy($id)
    {
        $salary =
            DriverSalary::find($id);

        if (!$salary) {

            return response()->json([
                'message' => 'الراتب غير موجود'
            ], 404);
        }
        // تسجيل العملية في سجل النظام
        \App\Models\ActivityLog::create([
            'user_id'     => request()->user_id ?? auth()->id(),
            'action'      => 'تم حذف راتب سائق',
            'record_id'   => $id,
            'description' => 'تم حذف راتب بقيمة: ' . $salary->amount,
        ]);

        $salary->delete();

        return response()->json([

            'message' => 'تم الحذف'

        ]);
    }
}