<?php

namespace App\Events;

use Illuminate\Auth\Events\Login;

/**
 * Class ApiLogin
 * @package App\Events
 */
class ApiLogin extends Login {
    protected $ip_addr;

    /**
     * ApiLogin constructor.
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param bool $remember
     * @param int $ip_addr
     */
    public function __construct(\Illuminate\Contracts\Auth\Authenticatable $user, $remember, $ip_addr = 0) {
        $this->ip_addr = $ip_addr;

        parent::__construct($user, $remember);
    }

    /**
     * @return mixed
     */
    public function getIpAddr() {
        return $this->ip_addr;
    }
}
