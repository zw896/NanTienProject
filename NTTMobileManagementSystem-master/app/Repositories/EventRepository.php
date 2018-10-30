<?php

namespace App\Repositories;

class EventRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model() {
        return \App\Models\Event::class;
    }
}
