<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseTestController extends Controller
{

    public function sendNotification(Messaging $messaging)
    {

        $deviceToken = "PUT_YOUR_TOKEN_HERE";

        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification(Notification::create(
                'اختبار إشعار',
                'تم إرسال الإشعار من Laravel بنجاح'
            ));

        $messaging->send($message);

        return response()->json([
            'message' => 'Notification sent successfully'
        ]);
    }

}