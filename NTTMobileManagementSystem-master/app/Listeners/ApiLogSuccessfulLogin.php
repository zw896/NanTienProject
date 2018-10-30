<?php

namespace App\Listeners;

use App\Events\ApiLogin;

/**
 * Class ApiLogSuccessfulLogin
 * @package App\Listeners
 */
class ApiLogSuccessfulLogin {
    /**
     * Handle the event.
     *
     * @param  ApiLogin $event
     * @return void
     */
    public function handle(ApiLogin $event) {
        $ipaddr = $event->getIpAddr();
        if (!$event->remember) {
            if ($event->user->login_ip != $ipaddr) {
                $event->user->login_ip = $ipaddr;
                $event->user->save();
            } else {
                $event->user->touch();
            }
        }
    }
}
