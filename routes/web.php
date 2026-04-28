<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/no', function () {

    // 1. الوصول إلى محرك المراسلة في فايربيس
    $messaging = app('firebase.messaging');

    // 2. تحديد اسم الموضوع
    $topic = 'student';

    // 3. محتوى الإشعار
    $titles = 'تنبيه من النظام 🔔';
    $body = 'هذا أول إشعار يتم إرساله عبر المعيار الجديد من سيرفر لارافل!';

    // 4. إنشاء الرسالة (✅ التصحيح هنا)
    $message = CloudMessage::new()
        ->withTarget('topic', $topic)
        ->withNotification(Notification::create($titles, $body))
        ->withData([
            'type' => 'news_update',
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'id' => '123'
        ]);

    try {
        // 5. إرسال الإشعار
        $messaging->send($message);

        return response()->json([
            'message' => 'تم إرسال الإشعار بنجاح!'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'حدث خطأ: ' . $e->getMessage()
        ], 500);
    }

});


