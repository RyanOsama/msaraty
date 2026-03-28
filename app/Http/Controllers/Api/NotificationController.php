<?php

namespace App\Http\Controllers\Api;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use App\Models\DeviceToken;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // عرض جميع الاشعارات
    public function index()
    {
        $notifications = Notification::all();

        return response()->json($notifications, 200);
    }

    // إضافة إشعار
    public function store(Request $request)
    {
        $request->validate([
            'sender_id' => ['nullable', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'target_group' => ['required', 'integer'],
            'type' => ['required', 'string', 'max:50'],
        ]);

        $notification = Notification::create([
            'sender_id' => $request->sender_id,
            'title' => $request->title,
            'message' => $request->message,
            'target_group' => $request->target_group,
            'type' => $request->type,
        ]);

        return response()->json($notification, 201);
    }

    // تعديل إشعار
    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'sender_id' => ['sometimes','nullable','integer'],
            'title' => ['sometimes','string','max:255'],
            'message' => ['sometimes','string'],
            'target_group' => ['sometimes','integer'],
            'type' => ['sometimes','string','max:50'],
        ]);

        $notification->update($request->only([
            'sender_id',
            'title',
            'message',
            'target_group',
            'type'
        ]));

        return response()->json([
            'message' => 'تم تعديل الاشعار بنجاح',
            'notification' => $notification,
        ]);
    }

    // حذف إشعار
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return response()->json([
            'message' => 'تم حذف الاشعار بنجاح',
        ]);
    }



public function sendFirebaseNotification($id)
{
    $notification = Notification::findOrFail($id);

    $tokens = DeviceToken::where('group', $notification->target_group)
        ->pluck('token')
        ->toArray();

    if(empty($tokens)){
        return response()->json([
            'message' => 'لا يوجد أجهزة لاستقبال الإشعار'
        ]);
    }

    $messaging = Firebase::messaging();

    foreach ($tokens as $token) {

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(
                FirebaseNotification::create(
                    $notification->title,
                    $notification->message
                )
            );

        $messaging->send($message);
    }

    return response()->json([
        'message' => 'تم إرسال الإشعار بنجاح'
    ]);
}
}