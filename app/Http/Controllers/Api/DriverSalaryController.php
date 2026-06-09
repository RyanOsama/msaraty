<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DriverSalary;

class DriverSalaryController extends Controller
{
    // عرض الرواتب
    public function index()
    {
        $salaries =
            DriverSalary::with(
                'driver'
            )
            ->latest()
            ->get();

        return response()->json(
            $salaries
        );
    }


    // إضافة راتب
    public function store(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'amount' => 'required|numeric',
            'for_month' => 'required|string',
            'status' => 'required|in:paid,unpaid',
        ]);

        $salary =
            DriverSalary::create([

                'driver_id' =>
                    $request->driver_id,

                'amount' =>
                    $request->amount,

                'for_month' =>
                    $request->for_month,

                'status' =>
                    $request->status,

            ]);

        return response()->json([
            'message' =>
                'تم إضافة الراتب بنجاح',

            'data' =>
                $salary
        ], 201);
    }


    // تعديل راتب
    public function update(
        Request $request,
        $id
    )
    {
        $salary =
            DriverSalary::find(
                $id
            );

        if (
            !$salary
        ) {

            return response()->json([
                'message' =>
                    'الراتب غير موجود'
            ], 404);

        }

        $request->validate([

            'driver_id' =>
                'sometimes|exists:drivers,id',

            'amount' =>
                'sometimes|numeric',

            'for_month' =>
                'sometimes|string',

            'status' =>
                'sometimes|in:paid,unpaid',

        ]);

        $salary->update(

            $request->only([

                'driver_id',

                'amount',

                'for_month',

                'status'

            ])

        );

        return response()->json([

            'message' =>
                'تم تعديل الراتب بنجاح',

            'data' =>
                $salary->fresh()

        ]);
    }


    // حذف راتب
    public function destroy($id)
    {
        $salary =
            DriverSalary::find(
                $id
            );

        if (
            !$salary
        ) {

            return response()->json([
                'message' =>
                    'الراتب غير موجود'
            ], 404);

        }

        $salary->delete();

        return response()->json([

            'message' =>
                'تم حذف الراتب'

        ]);
    }
}