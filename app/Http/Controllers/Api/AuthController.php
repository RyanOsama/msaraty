<?php

namespace App\Http\Controllers\Api;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
  use Illuminate\Support\Facades\Auth;
  use App\Http\Controllers\Api\AuthController;



class AuthController extends Controller
{
    /**
     * داله انشاء مستخدم من التطيبق
     */
   public function register(UserRequest $request)
{
    

    $user = User::create([
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'role_id'  => $request->role_id,
        'status'   => 'pending',
    ]);

    // return response()->json([
    //     'message' => 'تم إنشاء الحساب بنجاح',
    //     'data' => [
    //         'id'       => $user->id,
    //         'username' => $user->username,
    //         'role_id'  => $user->role_id,
    //         'status'   => $user->status,
    //     ],
    // ], 201);

     return response()->json([
       
            'id'       => $user->id,
            'username' => $user->username,
            'role_id'  => $user->role_id,
            'status'   => $user->status,
     
    ], 201);
}
  
//login user
public function login(LoginRequest $request)
{
    

    if (!Auth::attempt($request->only('username', 'password'))) {
        return response()->json([
            'message' => 'بيانات الدخول غير صحيحة'
        ], 401);
    }

    $user = Auth::user();
     //ينفع عند صابر
        return response()->json([
            'user'   => [
                'username' => $user->username,
                'role_id'  => $user->role_id,
                 'status' => $user->status,
            ],
        ], 200);
        

        // عند اسامه

        //     return response()->json([
       
          
        //         'username' => $user->username,
        //         'role_id'  => $user->role_id,
        //              'status' => $user->status, // pending | approved
           
        // ], 200);

        
    }
    


 //التحقق من الموافقه
public function checkStatus(Request $request)
{
    $user = User::where('username', $request->username)->first();

    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    return response()->json([
        'status' => $user->status
    ]);
}




}