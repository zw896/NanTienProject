<?php

namespace App\Components\JSendResponse\Services;

use App;
use App\Components\JSendResponse\ResultCode;

/**
 * Class JSendService
 * @package App\Components\JSendResponse\Services
 */
class JSendService {
    /**
     * @param $status
     * @param $code
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function JSendResponse($status, $code, array $data = null) {
        if (!is_null($data)) {
            if (array_key_exists('debug', $data)) {
                if (App::environment() === 'production') {
                    // in production environment don't display debug information
                    unset($data['debug']);
                }
            }
        }

        return response()->json(
            [
                'status' => $status,
                'code' => $code,
                'timestamp' => time(),
                'data' => $data,
            ]
        );
    }

    /**
     * @param int $code
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($code = 200, array $data = null) {
        return $this->jSendResponse(ResultCode::SUCCESS, $code, $data);
    }

    /**
     * @param int $code
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function fail($code = 400, array $data = null) {
        return $this->jSendResponse(ResultCode::FAIL, $code, $data);
    }

    /**
     * @param int $code
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($code = 500, array $data = null) {
        return $this->jSendResponse(ResultCode::ERROR, $code, $data);
    }
}
