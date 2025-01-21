<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) : JsonResponse
    {
        try {
            $fields = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string'
            ]);

            $user = User::query()->create($fields);
            $token = $user->createToken($request->name)->plainTextToken;

            return response()->json([
                "user" => $user,
                "token" => $token,
            ], 201);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Registration failed. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request) : JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|string|email|exists:users',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid credentials.',
                ], 401);
            }

            $token = $user->createToken($request->email)->plainTextToken;

            return response()->json([
                "user" => $user,
                "token" => $token,
            ], 200);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Login failed. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request) : JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Successfully logged out.',
            ], 200);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Logout failed. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
