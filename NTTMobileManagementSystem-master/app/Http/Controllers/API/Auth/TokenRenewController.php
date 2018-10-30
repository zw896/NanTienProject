<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use JSendResponse;

/**
 * Class TokenRenewController
 * @package app\Http\Controllers\Auth
 */
class TokenRenewController extends BaseController {
    /**
     * currently this function does't work
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    function renew() {
        return JSendResponse::success();
    }
}
