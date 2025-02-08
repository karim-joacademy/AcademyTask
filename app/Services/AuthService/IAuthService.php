<?php

namespace App\Services\AuthService;

use App\Http\Requests\AuthRequests\LoginRequest;
use App\Http\Requests\AuthRequests\RegisterRequest;
use Illuminate\Http\Request;

interface IAuthService
{
    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return array
     */
    public function register(RegisterRequest $request): array;

    /**
     * Handle user login.
     *
     * @param LoginRequest $request
     * @return array
     */
    public function login(LoginRequest $request): array;

    /**
     * Handle user logout.
     *
     * @param Request $request
     * @return array
     */
    public function logout(Request $request): array;
}
