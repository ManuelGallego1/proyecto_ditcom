<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string|max:255|exists:users,username',
                'password' => 'required|string|min:7',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], $e->status);
        }

        if (! Auth::attempt($request->only('username', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $tokenType = 'Bearer';
        $user = User::where('username', $request['username'])->firstOrFail();

        $user->tokens()->where('name', $tokenType)->delete();

        $token = $user->createToken($tokenType);

        return response()->json([
            'token' => $token->plainTextToken,
            'type' => $tokenType,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Token revoked'], 200);
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:7',
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], $e->status);
        }

        $user = User::create([
            'name' => $request['name'],
            'username' => $request['username'],
            'password' => bcrypt($request['password']),
        ]);

        $tokenType = 'Bearer';
        $token = $user->createToken($tokenType);

        return response()->json([
            'token' => $token->plainTextToken,
            'type' => $tokenType,
        ], 201);
    }
}
