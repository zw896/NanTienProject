<?php
/**
 * Created by PhpStorm.
 * User: Kotarou
 * Date: 2017/5/21
 * Time: 21:18
 */

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests;
use JSendResponse;

class ApiThrottleRequests extends ThrottleRequests {
    /**
     * Create a 'too many attempts' response.
     *
     * @param  string $key
     * @param  int $maxAttempts
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildResponse($key, $maxAttempts) {
        $retryAfter = $this->limiter->availableIn($key);

        return $this->addHeaders(
            JSendResponse::error(429, ['error' => 'Too many attempts, please slow down the request.']), $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );
    }
}
