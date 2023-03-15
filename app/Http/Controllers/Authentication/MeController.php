<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\MeRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\AuthenticationService;

class MeController extends Controller
{
    private AuthenticationService $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }
    public function __invoke(MeRequest $request)
    {
        return new UserResource($this->authenticationService->getAuthenticatedUser());
    }
}
