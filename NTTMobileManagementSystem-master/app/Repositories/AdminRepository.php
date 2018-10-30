<?php

namespace App\Repositories;

class AdminRepository extends BaseRepository {
    /**
     * @return string
     */
    public function model() {
        return \App\Roles\Admin::class;
    }
}
