<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users',
            'name' => 'required|string',
            'phone' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'ثبت‌نام با موفقیت انجام شد',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'login'    => 'required|string', // username یا phone
            'password' => 'required|string',
        ]);

        $user = User::where('username', $validated['login'])
            ->orWhere('phone', $validated['login'])
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'نام کاربری/موبایل یا رمز عبور نادرست است'], 401);
        }

        $token = base64_encode(Str::random(40));
        $user->forceFill(['api_token' => $token])->save();

        return response()->json([
            'message' => 'ورود موفقیت‌آمیز بود',
            'token'   => $token,
            'user'    => $user,
        ]);
    }
}
