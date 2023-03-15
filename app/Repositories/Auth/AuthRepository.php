<?php

namespace App\Repositories\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Repositories\Auth\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::guard('api')->attempt($credentials)) {
            $token = JWTAuth::fromUser(Auth::guard('api')->user());
            return Response()->json([
                'user' => Auth::guard('api')->user(),
                'token' => $token
            ], 200);
        }

        return Response()->json([
            'message' => 'These credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('api')->logout();

        return Response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }

    public function me(Request $request)
    {
        if (Auth::guard('api')->user() == null) {
            return Response()->json([
                'message' => 'You are not logged in'
            ], 401);
        }

        return Response()->json([
            'user' => Auth::user()
        ], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return Response()->json([
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => Role::where('name', 'customer')->first()->id
        ]);

        return Response()->json([
            'message' => 'Account created successfully',
            'user' => $user
        ], 200);
    }
}
