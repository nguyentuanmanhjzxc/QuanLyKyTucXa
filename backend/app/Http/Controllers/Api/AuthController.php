<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
        $user = User::where('username',$request->username)->first();
        
        if(!$user){
            return response()->json(['message' => 'Sai username'],401);
        }
        
        if (!Hash::check($request->password,$user->password)) 
        {
            return response()->json([
                'message' => 'Sai password'
            ],401);
        }

        if($user->status != 'active'){
                return response()->json([
                'message' => 'Tài khoản đã bị khóa'
            ],401);
        }   

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'user' => $user
        ]);
    }

    public function logout(){
        return response()->json([
            'message' => 'Đăng xuất thành công'
        ]);
    }
 
}
