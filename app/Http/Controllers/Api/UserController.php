<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;

class UserController extends Controller
{


//عرض الرولات
public function getRoles()
{
      $role = role::select(
                'id',
                'name',
            )
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($role
        ,200);
}
    /**
     * عرض جميع المستخدمين
     *///شغال عند صابر 
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

        return response()->json(     $users
        ,200);
    }
//     public function index()
// {
//     return User::select(
//         'id',
//         'username',
//         'role_id',
//         'status',
//         'created_at'
//     )->orderBy('id', 'desc')->get();
// }

public function store(Request $request)
{
     $user = User::create([
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'role_id'  => $request->role_id,
        'status'   => 'pending',
    ]);

   

     return response()->json([
       
            'id'       => $user->id,
            'username' => $user->username,
            'role_id'  => $user->role_id,
            'status'   => $user->status,
     
    ], 201);}



public function update(Request $request, $id)
{
    $user = \App\Models\User::find($id);

    if (!$user) {
        return response()->json([
            'message' => 'المستخدم غير موجود'
        ], 404);
    }

    $request->validate([
        'username' => 'sometimes|string|max:255|unique:users,username,' . $user->id,
        'role_id'  => 'sometimes|exists:roles,id',
        'status'   => 'sometimes|in:pending,approved,rejected',
    ]);

    $user->update($request->only([
        'username',
        'role_id',
        'status'
    ]));

    return response()->json([
        'message' => 'تم تعديل المستخدم بنجاح',
        'user' => $user
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

