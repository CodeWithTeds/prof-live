<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Http\Requests\auth\RegisterRequest;
use Illuminate\Http\Request;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    /**
     *
     * @param AuthService $authService
     *
     * Construction property promotion
     * about boilerplate
     *
     */
    public function __construct(private readonly AuthService $authService) {}

    /**
     *
     * @param AuthRequest $request
     * $return JsonResponse
     */
    public function login(AuthRequest $request): JsonResponse
    {
        $token = $this->authService->login($request->validated());

        return response()->json(['token' => $token,]);
    }

    /**
     *
     * @param AuthRequest $request
     * $return JsonResponse
     */
    public function Register(AuthRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());
        $token = $this->authService->login($request->validated());

        return $user->createToken('auth-token')->plainTextToken;
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());


        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
