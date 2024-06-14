<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\password;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $userdata = $request->validated();
        if (!Auth::attempt($userdata)) {
            return response()->json([
                'message' => 'No User Found'
            ], 401);
        }

        $user = User::where('email', $userdata['email'])->first();

        // dd($user->tokens);
        /**
         *limit tokens per user  
         */
        if ($user->tokens->count() > 0) {
            return response()->json([
                'user' => $user->name,
                'message' => 'Successfully Logged In',


            ]);
        } else {
            return response()->json([
                'user' => $user->name,
                'message' => 'Successfully Logged In',
                'token' => $user->createToken('auth_token')->plainTextToken,
                'token_type' => 'Bearer Token',

            ]);
        };
    }


    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }



    public function logout()
    {
        // Auth::user()->tokens()->delete();
        return [
            'message' => 'Succesfully Logged out'
        ];
    }
}
