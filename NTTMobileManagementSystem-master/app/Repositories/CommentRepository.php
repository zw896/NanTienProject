<?php

namespace App\Repositories;

class CommentRepository extends BaseRepository {
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model() {
        return \App\Models\Comment::class;
    }
}
