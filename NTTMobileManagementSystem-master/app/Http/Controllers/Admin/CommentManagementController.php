<?php

namespace App\Http\Controllers\Admin;

use App\Components\AjaxHelper\ajaxHandler;
use App\Components\AjaxHelper\Contracts\AjaxContract;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Repositories\CommentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use JSendResponse;

/**
 * Class CommentManagementController
 * @package App\Http\Controllers\Admin
 */
class CommentManagementController extends Controller implements AjaxContract {
    use ajaxHandler;

    /**
     * CommentManagementController constructor.
     * @param CommentRepository $repository
     */
    public function __construct(CommentRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCommentList() {
        return view('content.comment.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function ajaxDelete(Request $request) {
        $cid = $request->get('id');

        return $this->basicAjaxFunction(['id' => $cid],
            function ($parameter, $get) {
                try {
                    $this->repository->deleteWithPreNotify($get('id'));

                    return JSendResponse::success();
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(404, ['error' => 'Comment not found']);
                }
            }
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function ajaxMultiDelete(Request $request) {
        $ids = $request->get('ids');

        return $this->basicAjaxFunction(['ids' => $ids],
            function ($parameter, $get) {
                $result = ['success' => 0, 'fail' => 0];

                foreach ($get('ids') as $id) {
                    try {
                        $this->repository->deleteWithPreNotify($id);

                        ++$result['success'];
                    } catch (ModelNotFoundException $e) {
                        ++$result['fail'];
                    }
                }

                return JSendResponse::success(200, ['message' => 'Successfully deleted ' . $result['success'] . ' entries, fail ' . $result['fail']]);
            }
        );
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function ajaxToggle(Request $request) {
        $cid = $request->get('id');

        return $this->basicAjaxFunction(['cid' => $cid],
            function ($parameter, $get) {
                try {
                    $comment = $this->repository->find($get('cid'));
                    $comment->display = !$comment->display;
                    $comment->save();

                    return JSendResponse::success();
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(404, ['error' => 'Comment not found']);
                }
            }
        );
    }

    private function ajaxEdit(Request $request) {

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
                        $comments = Comment::with('event', 'user')
                            ->withCount('attachments')
                            ->where('content', 'like', "%" . $parameter['search'] . "%")
                            ->orderBy($get('sort'), $get('order'))
                            ->skip($get('offset'))
                            ->take($get('limit'))
                            ->get();

                        $total = Comment::where('content', 'like', "%" . $parameter['search'] . "%")
                            ->count();

                        $comments = ['total' => $total, 'data' => $comments];
                    } else {
                        $comments = Comment::with('event', 'user')
                            ->withCount('attachments')
                            ->orderBy($get('sort'), $get('order'))
                            ->skip($get('offset'))
                            ->take($get('limit'))
                            ->get();

                        $comments = ['total' => Comment::count(), 'data' => $comments];
                    }

                    return JSendResponse::success(200, $comments);
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(404, ['error' => 'Comment not found']);
                }
            }
        );
    }
}
