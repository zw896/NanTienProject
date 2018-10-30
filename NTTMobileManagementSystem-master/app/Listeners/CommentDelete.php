<?php

namespace App\Listeners;

use App\Events\PreModelDelete;
use App\Models\Comment;
use App\Repositories\AttachmentRepository;

/**
 * Class CommentDelete
 * @package App\Listeners
 */
class CommentDelete {
    private $repository;

    /**
     * CommentDelete constructor.
     * @param AttachmentRepository $repository
     */
    public function __construct(AttachmentRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @param PreModelDelete $event
     */
    public function handle(PreModelDelete $event) {
        // do pre delete
        if ($event->getModel() instanceof Comment) {
            $attachments = $event->getModel()->attachments;
            if (!$attachments->isEmpty()) {
                foreach ($attachments as $attachment) {
                    $this->repository->delete($attachment->id);
                }
            }
        }
    }
}
