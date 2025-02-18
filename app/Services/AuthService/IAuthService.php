<?php

namespace App\Services\AuthService;

use Illuminate\Http\Request;

interface IAuthService
{
    /**
     * Register a new user.
     *
     * @param Request $request
     * @return array
     */
    public function register(Request $request): array;

    /**
     * Handle user login.
     *
     * @param Request $request
     * @return array
     */
    public function login(Request $request): array;

    /**
     * Handle user logout.
     *
     * @param Request $request
     * @return array
     */
    public function logout(Request $request): array;
}
