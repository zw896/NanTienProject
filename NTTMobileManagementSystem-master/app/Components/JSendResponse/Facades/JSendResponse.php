<?php

namespace App\Components\JSendResponse\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class JSendResponse
 * @package App\Components\JSendResponse\Facades
 */
class JSendResponse extends Facade {
    /**
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'JSendResponseService';
    }
}
