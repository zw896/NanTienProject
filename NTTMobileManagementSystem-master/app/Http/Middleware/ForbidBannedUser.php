<?php

namespace App\Http\Middleware;

use Closure;
use Cog\Ban\Http\Middleware\ForbidBannedUser as BasicForbidBannedUser;
use Illuminate\Support\Facades\Auth;

class ForbidBannedUser extends BasicForbidBannedUser {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $user = $this->auth->user();

        if ($user && $user->isBanned()) {
            // force logout user
            Auth::logout();

            return redirect()->back()->withInput()->withErrors([
                'login' => 'This account is blocked.',
            ]);
        }

        return $next($request);
    }
}
