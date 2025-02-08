<?php

namespace App\Services\AuthService;

use App\Http\Requests\AuthRequests\LoginRequest;
use App\Http\Requests\AuthRequests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService implements IAuthService
{
    /**
     * Handle user registeration.
     *
     * @param RegisterRequest $request
     * @return array
     */
    public function register(RegisterRequest $request): array
    {
        try {
            $validated = $request->validated();

            $user = User::query()->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'type' => $validated['type'],
                'password' => bcrypt($validated['password']),
            ]);
            $user->assignRole($validated['type']);
            $token = $user->createToken('academytask')->plainTextToken;

            return [
                'success' => true,
                'user' => $user,
                'token' => $token,
                'status' => 201
            ];
        }
        catch (Exception $e) {
            Log::error("Error registering user: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'An error occurred while registering the user.',
                'error' => $e->getMessage(),
                'status' => 500
            ];
        }
    }

    
    /**
     * Handle user login.
     *
     * @param LoginRequest $request
     * @return array
     */
    public function login(LoginRequest $request): array
    {
        try {
            $validated = $request->validated();

            $user = User::query()->where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                throw new Exception('Invalid credentials');
            }

            $token = $user->createToken('academytask')->plainTextToken;

            return [
                'success' => true,
                'user' => $user,
                'token' => $token,
                'status' => 200,
            ];
        } catch (Exception $e) {
            Log::error("Error logging in user: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Invalid credentials',
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }


    /**
     * Handle user logout.
     *
     * @param Request $request
     * @return array
     */
    public function logout(Request $request): array
    {
        try {
            $user = $request->user();

            $user->tokens->each(function ($token) {
                $token->delete();
            });

            return [
                'success' => true,
                'status' => 200,
            ];
        }
        catch (Exception $e) {
            Log::error("Error logging out user: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Logout failed. Please try again later.',
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }
}

