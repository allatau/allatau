<?php

namespace App\Http\Controllers\API\Auth;

use App\Constants\AuthConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    use HttpResponses;

    /**
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function login(AuthRequest $request): JsonResponse
    {
        if (auth()->attempt($request->all())) {
            $user = auth()->user();

            $user->tokens()->delete();

            $success = $user->createToken('MyApp')->plainTextToken;

            return $this->success(['token' => $success], AuthConstants::LOGIN);
        }

        return $this->error([], AuthConstants::VALIDATION);
    }

    /**
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {

//        $user = auth()->user();
//
//        $user->tokens()->delete();
//
//        return $this->success([], AuthConstants::LOGOUT);

        // Get bearer token from the request
        $accessToken = $request->bearerToken();

        // Get access token from database
        $token = PersonalAccessToken::findToken($accessToken);

        // Revoke token
        $token->delete();
        return $this->success([], AuthConstants::LOGOUT);

    }

    /**
     * @return JsonResponse
     */
    public function details(Request $request): JsonResponse
    {
//        // Get bearer token from the request
        $accessToken = $request->bearerToken();

        // Get access token from database
        $token = PersonalAccessToken::findToken($accessToken);

        $user = $token->tokenable;

        return $this->success($user, '');


    }
}
