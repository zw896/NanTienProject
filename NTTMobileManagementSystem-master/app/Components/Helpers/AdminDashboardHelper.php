<?php

namespace App\Components\Helpers;

use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Event;
use App\Models\Feedback;
use App\Roles\User;

/**
 *
 * Class AdminDashboardHelper
 * @package App\Components\Helpers
 */
class AdminDashboardHelper {
    /**
     * @return array
     */
    public function render() {
        return [
            $this->buildData('Events', '/admin/content/event/', 'ion-ios-paper', 'bg-aqua', function () {
                return Event::count();
            }),

            $this->buildData('Comments', '/admin/content/comment/', 'ion-chatbubble-working', 'bg-red', function () {
                return Comment::count();
            }),

            $this->buildData('Feedback', '/admin/content/feedback/', 'ion-compose', 'bg-green', function () {
                return Feedback::count();
            }),

            $this->buildData('Users', '/admin/role/user/', 'ion-person', 'bg-yellow', function () {
                return User::count();
            }),

            $this->buildData('Images', '/admin/attachment/image/', 'ion-images', 'bg-teal', function () {
                return Attachment::where('type', '=', '0')->count();
            }),

            $this->buildData('Videos', '/admin/attachment/video/', 'ion-ios-videocam', 'bg-maroon', function () {
                return Attachment::where('type', '=', '1')->count();
            }),
        ];
    }

    /**
     * @param $title
     * @param $url
     * @param $icon
     * @param $colour
     * @param $countFunc
     * @return array
     */
    private function buildData($title, $url, $icon, $colour, $countFunc) {
        return [
            'count' => $countFunc(),
            'title' => $title,
            'icon' => $icon,
            'bck' => $colour,
            'url' => $url
        ];
    }
}
