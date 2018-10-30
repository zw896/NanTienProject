<?php

namespace App\Providers;

use App\Components\Helpers\MessageHelper;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        $helper = new MessageHelper();

        View::share('message', $helper->render());
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {

    }
}
