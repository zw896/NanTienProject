<?php

namespace App\Components\AjaxHelper;

use App\Components\AjaxHelper\Exceptions\AttributeNotFoundException;
use App\Components\Helpers\ExceptionFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use JSendResponse;

/**
 * Trait ajaxHandler
 * @package App\Components\AjaxHelper
 */
trait ajaxHandler {
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function ajaxHandler(Request $request) {
        try {
            if ($request->ajax()) {
                $methodName = $request->get('_function', null);

                if ($methodName != null) {
                    // if functionMapper is set, use the mapper otherwise, use auto mapper
                    if (isset($this->functionMapper)) {
                        if (array_key_exists($methodName, $this->functionMapper) && method_exists($this, $this->functionMapper[lcfirst($methodName)])) {
                            return call_user_func([$this, $this->functionMapper[$methodName]], $request);
                        } else {
                            return JSendResponse::error(500, ['error' => 'Function not found', 'processor' => 'Manual mapper']);
                        }
                    } else {
                        // auto mapper here
                        $methodName = ucfirst($methodName);
                        if (method_exists($this, 'ajax' . $methodName)) {
                            return call_user_func([$this, 'ajax' . $methodName], $request);
                        } else {
                            return JSendResponse::error(500, ['error' => 'Function not found', 'processor' => 'Auto mapper']);
                        }
                    }
                } else {
                    return JSendResponse::error(500, ['error' => 'Missing function name']);
                }
            } else {
                return JSendResponse::fail(400, ['error' => 'Not an ajax call']);
            }
        } catch (\Exception $e) {
            Log::critical('ajax handler exception', ['trace' => ExceptionFormatter::jTraceEx($e)]);
            return JSendResponse::error(500, ['error' => 'An error occurred while processing your request.']);
        }
    }

    /**
     * @param array $parameters
     * @param $func
     * @return \Illuminate\Http\JsonResponse
     */
    public function basicAjaxFunction(array $parameters, $func) {
        $get = function ($key) use ($parameters) {
            if (array_key_exists($key, $parameters) && !is_null($parameters[$key])) {
                return $parameters[$key];
            } else {
                throw new AttributeNotFoundException($key);
            }
        };

        try {
            if (is_callable($func)) {
                return $func($parameters, $get);
            } else {
                return JSendResponse::error(500, ['error' => 'Internal error']);
            }
        } catch (AttributeNotFoundException $e) {
            return JSendResponse::fail(400, ['error' => 'Missing parameter ' . $e->getMessage()]);
        }
    }
}
