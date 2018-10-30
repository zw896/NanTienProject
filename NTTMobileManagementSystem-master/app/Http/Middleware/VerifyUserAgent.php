<?php

namespace App\Http\Middleware;

use Closure;
use JSendResponse;

class VerifyUserAgent {
    private $white_list;

    /**
     * VerifyUserAgent constructor.
     */
    public function __construct() {
        // now the middleware is configurable via admin page
        // default value is allow all incoming request
        /*		$this->white_list = unserialize(OptionHelper::getOrRemember('api_ua', serialize(['*'])));*/

        // TODO make this dynamic, should not be hardcoded
        $this->white_list = ['test'];
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        // deal with wildcard
        if (in_array('*', $this->white_list)) {
            return $next($request);
        } else if (!in_array($request->header('User-Agent'), $this->white_list)) {
            return JSendResponse::error(403, ['error' => 'invalid_client']);
        }

        return $next($request);
    }
}
