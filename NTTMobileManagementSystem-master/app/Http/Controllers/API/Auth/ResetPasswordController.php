<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Models\ResetPassword;
use App\Roles\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use JSendResponse;

class ResetPasswordController extends BaseController {
    public function getToken(Request $request) {
        $username = $request->get('username');
        $email = $request->get('email');

        if (!empty($username) && empty(!$email)) {
            try {
                $user = User::where('username', '=', $username)
                    ->firstOrFail();

                // all success
                if ($user->email == $email) {
                    if ($user->resetPassword == null) {
                        ResetPassword::create(['uid' => $user->id, 'hash' => str_random(8), 'expire' => date("Y-m-d H:i:s", time() + 12 * 3600)]);
                    } else if (strtotime($user->resetPassword->expire) < time()) {
                        $user->resetPassword->hash = str_random(8);
                        $user->resetPassword->expire = date("Y-m-d H:i:s", time() + 12 * 3600);
                        $user->resetPassword->save();
                    }

                    return JSendResponse::success(200);
                } else {
                    return JSendResponse::fail(404, ['error' => 'incorrect email']);
                }

            } catch (ModelNotFoundException $e) {
                return JSendResponse::fail(404, ['error' => 'use not found']);
            }
        } else {
            return JSendResponse::fail(400, ['error' => 'username and email must not be empty']);
        }
    }

    public function handleReset(Request $request) {
        $username = $request->get('username');
        $token = $request->get('token');

        if (!empty($username) && empty(!$token)) {
            try {
                $user = User::where('username', '=', $username)
                    ->firstOrFail();

                // all success
                if ($user->resetPassword != null) {
                    // validate timestamp
                    if (strtotime($user->resetPassword->expire) >= time()) {
                        // handle reset
                        if ($user->resetPassword->token == $token) {
                            return JSendResponse::success(200);

                        } else {
                            return JSendResponse::fail(400, ['error' => 'Incorrect token']);
                        }

                    } else {
                        $user->resetPassword->delete();
                        return JSendResponse::fail(100, ['error' => 'Token expired']);
                    }

                } else {
                    return JSendResponse::fail(404, ['error' => 'Request not found']);
                }

            } catch (ModelNotFoundException $e) {
                return JSendResponse::fail(404, ['error' => 'User not found']);
            }
        } else {
            return JSendResponse::fail(400, ['error' => 'Username and token must be filled']);
        }

    }
}
