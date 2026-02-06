<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * عرض جميع المستخدمين
     */
    public function index()
    {
        $users = User::select(
                'id',
                'username',
                'role_id',
                'status',
                'created_at'
            )
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'users' => $users
        ], 200);
    }


     public function update(Request $request, $id)
    {
        $user = \App\Models\User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'المستخدم غير موجود'
            ], 404);
        }

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'role_id'  => 'required|exists:roles,id',
            'status'   => 'required|in:pending,approved,rejected',
        ]);

        $user->update([
            'username' => $request->username,
            'role_id'  => $request->role_id,
            'status'   => $request->status,
        ]);

        return response()->json([
            'message' => 'تم تعديل المستخدم بنجاح',
            'user' => [
                'id'       => $user->id,
                'username' => $user->username,
                'role_id'  => $user->role_id,
                'status'   => $user->status,
            ],
        ], 200);
    }

    /**
 * حذف مستخدم
 */
public function destroy($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'message' => 'المستخدم غير موجود'
        ], 404);
    }

    $user->delete();

    return response()->json([
        'message' => 'تم حذف المستخدم بنجاح'
    ], 200);
}


}

