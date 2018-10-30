<?php

namespace App\Http\Controllers\API\Auth;

use App\Events\ApiLogin;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use JSendResponse;

class LoginController extends BaseController {
    public function authenticate(Request $request) {
        // grab credentials from the request
        $credentials = $request->only('username', 'password');

        // attempt to verify the credentials and create a token for the user
        if (!$token = $this->guard()->attempt($credentials)) {
            return JSendResponse::fail(403, ['error' => 'invalid_credentials']);
        }

        // deal with banned account
        if ($this->guard()->user()->isBanned()) {
            return JSendResponse::fail(403, ['error' => 'account_banned']);
        }

        // publish login event
        event(new ApiLogin($this->guard()->user(), false, request()->ip()));

        // all good so return the token
        return JSendResponse::success(200, ['token' => $token]);
    }
}
