<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
public function loginUser(Request $request)
    {
        Log::info('juhu');
        $credentials = $request->only('email', 'password');
        Log::info('Attempting login for user:', ['email' => $credentials['email']]);

        if (Auth::attempt($credentials)) {
            Log::info('wuhu');
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);

            Log::info('Login successful for user:', ['email' => $credentials['email']]);
            return response()->json(['token' => $token]);
        }

        Log::warning('Login failed for user:', ['email' => $credentials['email']]);
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(Request $request)
    {
        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me()
    {
        return response()->json(Auth::user());
    }
}