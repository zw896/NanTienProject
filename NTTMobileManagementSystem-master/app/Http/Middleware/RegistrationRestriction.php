<?php

namespace App\Http\Middleware;

use App\Components\Helpers\OptionHelper;
use Closure;

/**
 * Class RegistrationRestriction
 * @package App\Http\Middleware
 */
class RegistrationRestriction {
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle($request, Closure $next) {
        if (OptionHelper::get('allow_register') === 'false') {
            return redirect('/');
        }

        return $next($request);
    }
}
