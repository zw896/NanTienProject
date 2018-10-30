<?php

namespace App\Http\Controllers\Admin;

use App\Components\AjaxHelper\ajaxHandler;
use App\Components\AjaxHelper\Contracts\AjaxContract;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Roles\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use JSendResponse;

/**
 * Class UserProfileController
 * @package App\Http\Controllers\Admin
 */
class UserProfileController extends Controller implements AjaxContract {
    use ajaxHandler;

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function getUserProfile($id) {
        try {
            $user = User::findOrFail($id);
            $comments = Comment::where('uid', '=', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return view("role.user.profile", compact('user', 'comments'))->with('comment_count', $comments->count());
        } catch (ModelNotFoundException $e) {
            return abort(404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function ajaxHasMoreComment(Request $request) {
        $uid = $request->get('uid');
        $loaded = $request->get('loaded');

        return $this->basicAjaxFunction(['uid' => $uid, 'loaded' => $loaded],
            function ($parameter, $get) {
                try {
                    $user = User::withCount('comments')
                        ->findOrFail($get('uid'));

                    return JSendResponse::success(200, ['hasMore' => ($user->comments_count > $get('loaded'))]);
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(404, ['error' => 'User not found']);
                }
            }
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function ajaxLoadMore(Request $request) {
        $uid = $request->get('uid');
        $page = ($request->get('loaded') / 5);

        return $this->basicAjaxFunction(['uid' => $uid, 'page' => $page],
            function ($parameter, $get) {
                try {
                    $comments = Comment::where('uid', '=', $get('uid'))
                        ->with('event')
                        ->orderBy('created_at', 'desc')
                        ->offset(5 * $get('page'))
                        ->limit(5)
                        ->get();

                    return JSendResponse::success(200, ['comments' => $comments, 'count' => $comments->count()]);
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(404, ['error' => 'User not found']);
                }
            }
        );
    }
}
