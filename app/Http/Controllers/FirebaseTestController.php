<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Messaging;

class FirebaseTestController extends Controller
{
    public function test(Messaging $messaging)
    {
        return response()->json([
            'message' => 'Firebase connected successfully'
        ]);
    }
}