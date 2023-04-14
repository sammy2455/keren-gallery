<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\RefreshRequest;
use App\Http\Services\AuthenticationService;
use Illuminate\Http\JsonResponse;

class      RefreshController extends Controller
{
    private AuthenticationService $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * Refresh
     *
     * This end point allows you to renew the entry ticket to Samara's services with a new expiration time.
     *
     * @authenticated
     * @group Authentication
     *
     * @responseFile status=200 scenario=OK storage/scribe/auth/auth.200.json
     * @response status=401 scenario=Unauthorized {"success": false, "message": "Unauthorized"}
     *
     * @return JsonResponse
     */
    public function __invoke(RefreshRequest $request)
    {
        // Get new token
        $token = $this->authenticationService->refresh();

        //
        return $this->authenticationService->respondWithToken($token);
    }
}
