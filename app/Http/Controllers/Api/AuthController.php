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
          'full_name' => $request->full_name, 
        'phone'     => $request->phone,     
    ]);

 

     return response()->json([
       
            'id'       => $user->id,
            'username' => $user->username,
            'role_id'  => $user->role_id,
            'status'   => $user->status,
            'full_name' => $user->full_name, 
            'phone'     => $user->phone,
     
    ], 201);
}
  
//login user
public function login(loginRequest $request)
{
    

    if (!Auth::attempt($request->only('username', 'password'))) {
        return response()->json([
            'message' => 'بيانات الدخول غير صحيحة'
        ], 401);
    }

    $user = Auth::user();
     //ينفع عند صابر
        return response()->json([
            'status' => $user->status,
            'user'   => [
            'id'       => $user->id,
            'username' => $user->username,
            'role_id'  => $user->role_id,
            'full_name' => $user->full_name, 
            'phone'     => $user->phone,
        
                 
            ],
        ], 200);
        

     
        
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