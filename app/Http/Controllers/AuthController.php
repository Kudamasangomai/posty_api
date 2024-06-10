<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\password;

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
                'message' => 'No User Found'
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
        $data = $request->validate([
            'name'=> 'required',
            'email'=> 'required|email',
            'password'=> 'required'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }



    public function logout()
    {
        Auth::user()->tokens()->delete();
        return [
            'message' => 'Succesfully Logged out'
        ];
    }
}
