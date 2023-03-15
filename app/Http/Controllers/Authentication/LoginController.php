<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Services\AuthenticationService;

class LoginController extends Controller
{
    private AuthenticationService $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function __invoke(LoginRequest $request)
    {
        // Build credentials to authenticate
        $token = $this->authenticateUser($request);

        //
        return $this->authenticationService->respondWithToken($token);
    }

    private function authenticateUser(LoginRequest $request): string
    {
        $email = $request->post('email');
        $password = $request->post('password');

        return $this->authenticationService->login($email, $password);
    }
}
