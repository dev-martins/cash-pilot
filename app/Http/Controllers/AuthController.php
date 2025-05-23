<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\OTPRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $response = $this->authService->register($data);

        return response()->json($response, 201);
    }

    /**
     * Login e enviar OTP.
     */
    public function login(AuthRequest $request)
    {
        $response = $this->authService->login($request->validated());

        return response()->json($response['data'], $response['status']);
    }

    /**
     * Verificar OTP/ retornar Bearer token.
     */
    public function verifyOTP(OTPRequest $request)
    {
        $response = $this->authService->verifyOTP($request->validated());

        return response()->json($response['data'], $response['status']);
    }
}
