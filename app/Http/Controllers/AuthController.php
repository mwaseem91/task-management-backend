<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function user() 
    {
        $user = auth()->user();
        $user = new UserResource($user);
      
        return response()->json([
            'status' => true,
            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => 'Validation failed',
                'message' => $validator->errors(),
            ],419);
        }

        $user = User::where('email', $request->email)->first();

        if($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('userToken')->plainTextToken;
            $user = new UserResource($user);
            $data = compact('user', 'token');
            return response()->json([
                'status' => true,
                'data' => $data, 
            ]);

        } else {
            $message = 'Invalid email or password';
            return response()->json($message, 419);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => 'Validation failed',
                'message' => $validator->errors(),
            ], 419);
        }

        $user = User::create($request->only(['name', 'email', 'password']));
        $user = new UserResource($user);

        $token = $user->createToken('userToken')->plainTextToken;
        $data = compact('user', 'token');
      
        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }
}
