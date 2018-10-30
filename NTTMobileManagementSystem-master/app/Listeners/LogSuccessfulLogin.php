<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

/**
 * Class LogSuccessfulLogin
 * @package App\Listeners
 */
class LogSuccessfulLogin {
    /**
     * Handle the event.
     *
     * @param  Login $event
     * @return void
     */
    public function handle(Login $event) {
        // only handle direct login (enter username and password)
        if (!$event->remember) {
            $event->user->last_login = date('Y-m-d H:i:s');
            $event->user->save();
        }
    }
}
