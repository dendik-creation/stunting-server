<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::with('puskesmas')
            ->where('username' , $request->username)
            ->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'Username atau password salah'], 401);
        }
        $data = [
            'status' => true,
            'message' => 'Login success',
            'data' => [
                'user' => $user,
                'access_token' => $user->createToken('auth_token')->plainTextToken,
            ],
        ];
        return response()->json($data, 200);
    }

    public function logout(Request $request){
        if(!empty(auth()->user())){
            $request->user()->currentAccessToken()->delete();
            return response()->json(['status' => true, 'message' => 'Logout success'], 200);
        }else{
            return response()->json(['status' => false, 'message' => 'Logout failed'], 401);
        }
    }
}
