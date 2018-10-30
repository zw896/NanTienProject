<?php

namespace App\Components\JSendResponse\Providers;

use App\Components\JSendResponse\Services\JSendService;
use Illuminate\Support\ServiceProvider;

/**
 * Class JSendResponseServiceProvider
 * @package App\Components\JSendResponse\Providers
 */
class JSendResponseServiceProvider extends ServiceProvider {
    /**
     *
     */
    public function register() {
        $this->app->singleton('JSendResponseService', function () {
            return new JSendService();
        });
    }
}
