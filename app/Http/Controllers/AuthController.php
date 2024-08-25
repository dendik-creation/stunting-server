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
            return response()->json(['status' => 'fail', 'message' => 'Username or password is incorrect'], 401);
        }
        $data = [
            'status' => 'success',
            'message' => 'Login success',
            'data' => [
                'user' => $user,
                'access_token' => $user->createToken('auth_token')->plainTextToken,
            ],
        ];
        return response()->json($data, 200, [], JSON_NUMERIC_CHECK);
    }

    public function logout(Request $request){
        if(!empty(auth()->user())){
            $request->user()->currentAccessToken()->delete();
            return response()->json(['status' => 'success', 'message' => 'Logout success'], 200);
        }else{
            return response()->json(['status' => 'fail', 'message' => 'Logout failed'], 401);
        }
    }
}
