<?php

namespace App\Http\Controllers\API;

use App\Models\Feedback;
use Illuminate\Http\Request;
use JSendResponse;

/**
 * Class FeedbackController
 * @package App\Http\Controllers
 */
class FeedbackController extends BaseController {
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request) {
        $content = $request->get('content');

        // if no content passed
        if (!empty($content)) {
            Feedback::create(['uid' => $this->guard()->id(), 'content' => $content]);
        } else {
            return JSendResponse::fail(400, ['error' => 'content_absent']);
        }

        return JSendResponse::success();
    }
}
