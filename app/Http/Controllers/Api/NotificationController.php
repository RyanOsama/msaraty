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
public function send(Request $request)
{
    // 1. التحقق من البيانات
    $request->validate([
        'title' => 'required|string|max:255',
        'message' => 'required|string',
        'type' => 'required|string|max:50',
        'target_group' => 'required|integer',
        'sender_id' => 'nullable|integer', // ✅ اختياري
    ]);

    $title = $request->title;
    $body = $request->message;

    // 2. تحديد التوبك حسب الفئة
    switch ($request->target_group) {
        case 1:
            $topic = 'student';
            break;
        case 2:
            $topic = 'driver';
            break;
        case 3:
            $topic = 'admin';
            break;
        case 4:
            $topic = 'all';
            break;
        default:
            $topic = 'student';
    }

    // 3. حفظ في DB (السندر إذا ما جاء = null)
    $notification = Notification::create([
        'sender_id' => $request->sender_id ?? null, // ✅ هذا المهم
        'title' => $title,
        'message' => $body,
        'type' => $request->type,
        'target_group' => $request->target_group,
    ]);

    try {
        $messaging = app('firebase.messaging');

        $message = CloudMessage::new()
            ->withTarget('topic', $topic)
            ->withNotification(FirebaseNotification::create($title, $body))
            ->withData([
                'type' => $request->type,
                'id' => (string)$notification->id,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ]);

        $messaging->send($message);

        return response()->json([
            'message' => 'تم حفظ وإرسال الإشعار بنجاح',
            'notification' => $notification
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'تم الحفظ لكن فشل الإرسال: ' . $e->getMessage()
        ], 500);
    }
}
}