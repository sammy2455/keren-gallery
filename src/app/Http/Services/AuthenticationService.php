<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticationService
{
    public function login(string $email, string $password): string
    {
        // Authenticate user in to API
        $credentials = [
            'email' => $email,
            'password' => $password,
        ];

        // Get token user
        if (!Auth::attempt($credentials)) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'You have entered an incorrect e-mail address or password'
            ], 401));
        }

        $user = User::where('email', $credentials['email'])->firstOrFail();

        if (!is_null($user->tokens())) {
            $user->tokens()->delete();
        }

        return $user->createToken('auth_token')->plainTextToken;
    }

    public function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'success' => true,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => env('JWT_TTL', 10080)
        ]);
    }

    public function logout(): void
    {
        Auth::user()->currentAccessToken()->delete();
    }

    public function refresh(): string
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        return $user->createToken('auth_token')->plainTextToken;
    }

    public function getAuthenticatedUser(): User
    {
        return Auth::user();
    }

}
