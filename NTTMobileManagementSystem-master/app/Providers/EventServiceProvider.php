<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \Illuminate\Auth\Events\Login::class => [
            \App\Listeners\LogSuccessfulLogin::class,
        ],

        \App\Events\ApiLogin::class => [
            \App\Listeners\ApiLogSuccessfulLogin::class,
        ],

        \App\Events\PreModelDelete::class => [
            \App\Listeners\CommentDelete::class,
            \App\Listeners\EventDelete::class,
            \App\Listeners\UserDelete::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot() {
        parent::boot();
    }
}
