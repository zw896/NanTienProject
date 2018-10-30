<?php

namespace App\Listeners;

use App\Events\PreModelDelete;
use App\Models\Event;
use App\Repositories\CommentRepository;

/**
 * Class CommentDelete
 * @package App\Listeners
 */
class EventDelete {
    private $repository;

    /**
     * EventDelete constructor.
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
        if ($event->getModel() instanceof Event) {
            $comments = $event->getModel()->comments;
            if (!$comments->isEmpty()) {
                foreach ($comments as $comment) {
                    $this->repository->deleteWithPreNotify($comment->id);
                }
            }
        }
    }
}
