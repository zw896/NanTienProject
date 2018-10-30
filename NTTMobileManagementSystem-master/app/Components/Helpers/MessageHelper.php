<?php

namespace App\Components\Helpers;

use App\Models\Event;
use App\Models\Message;
use Illuminate\Database\QueryException;

/**
 * Class MessageHelper
 * @package App\Components\Helpers
 */
class MessageHelper {
    /**
     * @return array|null
     */
    public function render() {
        if ($this->isEnable()) {
            return [
                'count' => $this->count(),
                'data' => $this->getMessage(),
            ];
        } else {
            return null;
        }
    }

    /**
     * @return bool
     */
    private function isEnable() {
        try {
            Message::count();
            return true;
        } catch (QueryException $e) {
            return false;
        }
    }

    /**
     * @return mixed
     */
    private function count() {
        return Message::count() + ($this->hasPendingEvent() ? 1 : 0);
    }

    /**
     * @return bool
     */
    private function hasPendingEvent() {
        return ($this->getPendingEventNum() > 0);
    }

    /**
     * return number of pending event for publish and push
     * @return mixed
     */
    private function getPendingEventNum() {
        // get pending message
        return Event::where('published', '=', '0')
            ->where('pushed', '=', '0')
            ->count();
    }

    /**
     * @param int $limit
     * @return array
     */
    private function getMessage($limit = 5) {
        $array = [];

        $pending = $this->getPendingEventNum();
        if ($pending > 0) {
            $array[] = [
                'title' => $pending . " Event are pending for push.",
                'type' => 'event',
                "content" => '',
                'url' => '/admin/content/event'
            ];
            --$limit;
        }

        // message from database
        $messages = Message::where('viewed', '=', '0')
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();

        foreach ($messages as $message) {
            $array[] = [
                'title' => $message->title,
                'type' => $message->type,
                "content" => $message->content,
                'url' => $message->url
            ];
        }

        return $array;
    }
}
