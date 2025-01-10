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
        $credentials = $request->only('email', 'password');
        Log::info('Attempting login for user:', ['email' => $credentials['email']]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);

            Log::info('Login successful for user:', ['email' => $credentials['email']]);
            return redirect()->intended('/dashboard')->with('token', $token);
        }

        Log::warning('Login failed for user:', ['email' => $credentials['email']]);
        return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
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