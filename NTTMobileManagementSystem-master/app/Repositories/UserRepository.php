<?php

namespace App\Repositories;


class UserRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model() {
        return \App\Roles\User::class;
    }
}
