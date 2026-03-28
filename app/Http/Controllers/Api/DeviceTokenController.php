<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeviceToken;

class DeviceTokenController extends Controller
{

    // حفظ توكن الجهاز القادم من Flutter
    public function saveToken(Request $request)
    {

        $request->validate([
            'token' => 'required'
        ]);

        DeviceToken::updateOrCreate(

            [
                'token' => $request->token
            ],

            [
                'user_id' => $request->user_id,
                'group' => $request->group ?? 1
            ]

        );

        return response()->json([
            'message' => 'Device token registered'
        ]);
    }

}