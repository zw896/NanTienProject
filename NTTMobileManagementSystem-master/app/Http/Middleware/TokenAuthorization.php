<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JSendResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenAuthorization {
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next) {
        try {
            // check the token
            JWTAuth::parseToken()->authenticate();

            // check user
            $user = $this->guard()->user();
            if (!$user) {
                return JSendResponse::fail(404, ['error' => 'user_not_found']);
            }

            if ($user->isBanned()) {
                return JSendResponse::fail(403, ['error' => 'account_banned']);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return JSendResponse::fail(401, ['error' => 'token_expired']);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return JSendResponse::fail(401, ['error' => 'token_invalid']);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return JSendResponse::fail(401, ['error' => 'token_absent']);
        }

        return $next($request);
    }

    /**
     * Get the guard to be used by all sub-class
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard() {
        return Auth::guard('api');
    }
}
