<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequests\LoginRequest;
use App\Http\Requests\AuthRequests\RegisterRequest;
use App\Services\AuthService\IAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request);

        if (!$result['success']) {
            return response()->json([
                'message' => 'Registration failed. Please try again later.',
                'error' => $result['error'] ?? null
            ], $result['status']);
        }

        return response()->json([
            'user' => $result['user'],
            'token' => $result['token']
        ], 201);
    }

    /**
     * Handle user login.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request);

        if (!$result['success']) {
            return response()->json([
                'message' => 'Login failed. Please try again later.',
                'error' => $result['error'] ?? null,
            ], $result['status']);
        }

        return response()->json([
            'user' => $result['user'],
            'token' => $result['token'],
        ], 200);
    }

    /**
     * Handle user logout.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $result = $this->authService->logout($request);

        if (!$result['success']) {
            return response()->json([
                'message' => 'Logout failed. Please try again later.',
                'error' => $result['error'] ?? null,
            ], $result['status']);
        }

        return response()->json([
            'message' => 'Successfully logged out.',
        ], 200);
    }
}
