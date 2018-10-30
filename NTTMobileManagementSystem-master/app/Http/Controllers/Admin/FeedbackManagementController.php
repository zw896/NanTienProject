<?php

namespace App\Http\Controllers\Admin;

use App\Components\AjaxHelper\ajaxHandler;
use App\Components\AjaxHelper\Contracts\AjaxContract;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use JSendResponse;

/**
 * Class FeedbackManagementController
 * @package App\Http\Controllers\Admin
 */
class FeedbackManagementController extends Controller implements AjaxContract {
    use ajaxHandler;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFeedbackList() {
        return view('content.feedback.index');
    }

    private function ajaxDelete(Request $request) {
        $cid = $request->get('cid');

        return $this->basicAjaxFunction(['cid' => $cid],
            function ($parameter, $get) {
                try {
                    $feedback = Feedback::findOrFail($get('cid'));
                    $feedback->delete();

                    return JSendResponse::success();
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(404, ['error' => 'Feedback not found']);
                }
            }
        );
    }

    private function ajaxGetTable(Request $request) {
        $limit = $request->get('limit');
        $offset = $request->get('offset');
        $search = $request->get('search');
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');

        return $this->basicAjaxFunction(['limit' => $limit, 'offset' => $offset, 'search' => $search, 'sort' => $sort, 'order' => $order],
            function ($parameter, $get) {
                try {
                    if (!empty($parameter['search'])) {
                        $feedback = Feedback::with('user')
                            ->where('content', 'like', "%" . $parameter['search'] . "%")
                            ->orderBy($get('sort'), $get('order'))
                            ->skip($get('offset'))
                            ->take($get('limit'))
                            ->get();

                        $feedback = ['total' => count($feedback), 'data' => $feedback];
                    } else {
                        $feedback = Feedback::with('user')
                            ->orderBy($get('sort'), $get('order'))
                            ->skip($get('offset'))
                            ->take($get('limit'))
                            ->get();

                        $feedback = ['total' => Feedback::count(), 'data' => $feedback];
                    }

                    return JSendResponse::success(200, $feedback);
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(404, ['error' => 'Feedback not found']);
                }
            }
        );
    }

}