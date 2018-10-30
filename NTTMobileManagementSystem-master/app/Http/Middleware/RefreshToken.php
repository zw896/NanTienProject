<?php

namespace App\Http\Middleware;

use Closure;
use JSendResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class RefreshToken Extends BaseMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $response = $next($request);
        try {
            $token = $this->auth->setRequest($request)->parseToken()->refresh();
            $this->auth->setToken($token); // <-- This one will let request through without blacklist error
        } catch (TokenExpiredException $e) {
            return JSendResponse::fail(401, ['error' => 'token_expired']);
        } catch (JWTException $e) {
            return JSendResponse::fail(401, ['error' => 'token_invalid']);
        }

        // send the refreshed token back to the client
        $response->headers->set('X-Token', $token);

        return $response;
    }
}
