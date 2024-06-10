<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'=>'required|email',
            'password' => 'required'
        ]);
        
        if(!Auth::attempt($validated)){
            return response()->json([
                'message' => 'No User found'
            ],401);
        }

        $user = User::where('email',$validated['email'])->first();
        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'token_type'=>'Bearer Token'
        ]);
  
    }


    public function register(Request $request)
    {
        
    }
}
