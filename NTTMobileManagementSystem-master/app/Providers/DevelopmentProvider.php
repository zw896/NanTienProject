<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DevelopmentProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            $this->app->register(\Laravel\Dusk\DuskServiceProvider::class);
        }
    }
}
