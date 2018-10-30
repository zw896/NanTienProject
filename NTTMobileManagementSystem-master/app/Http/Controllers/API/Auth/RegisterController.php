<?php

namespace App\Http\Controllers\API\Auth;

use App\Events\ApiLogin;
use App\Http\Controllers\API\BaseController;
use App\Roles\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JSendResponse;

class RegisterController extends BaseController {
    public function register(Request $request) {
        // create an validator
        $validator = $this->validator($request->all());

        // check the result
        if ($validator->fails()) {

            return JSendResponse::fail(400, ['error' => $validator->messages()]);
        }

        // register user and publish an event (for the system internal use)
        event(new Registered($user = $this->create($request->all())));

        // publish login event
        event(new ApiLogin($user, false, request()->ip()));

        // get the token
        $credentials = $request->only('username', 'password');
        $token = $this->guard()->attempt($credentials);

        return JSendResponse::success(200, ['token' => $token]);
    }

    private function validator(array $data) {
        return Validator::make($data, [
            'username' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'gender' => 'required|integer|min:0|max:2',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    private function create(array $data) {
        return User::create([
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
            'email' => $data['email'],
            'gender' => $data['gender'],
            'register_ip' => request()->ip(),
            'login_ip' => request()->ip(),
            'facebook' => 0,
        ]);
    }
}