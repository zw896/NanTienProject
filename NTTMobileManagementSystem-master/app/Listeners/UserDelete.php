<?php

namespace App\Listeners;

use App\Events\PreModelDelete;
use App\Repositories\CommentRepository;
use App\Roles\User;

/**
 * Class CommentDelete
 * @package App\Listeners
 */
class UserDelete {
    private $repository;

    /**
     * UserDelete constructor.
     * @param CommentRepository $repository
     */
    public function __construct(CommentRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @param PreModelDelete $event
     */
    public function handle(PreModelDelete $event) {
        // do pre delete
        if ($event->getModel() instanceof User) {
            $comments = $event->getModel()->comments;
            if (!$comments->isEmpty()) {
                foreach ($comments as $comment) {
                    $this->repository->deleteWithPreNotify($comment->id);
                }
            }
        }
    }
}
