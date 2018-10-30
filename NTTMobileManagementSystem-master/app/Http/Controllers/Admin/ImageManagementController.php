<?php

namespace App\Http\Controllers\Admin;

use App\Components\AjaxHelper\ajaxHandler;
use App\Components\AjaxHelper\Contracts\AjaxContract;
use App\Http\Controllers\Controller;
use App\Repositories\AttachmentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use JSendResponse;

/**
 * Class ImageManagementController
 * @package App\Http\Controllers\Admin
 */
class ImageManagementController extends Controller implements AjaxContract {
    use ajaxHandler;

    /**
     * ImageManagementController constructor.
     * @param AttachmentRepository $repository
     */
    public function __construct(AttachmentRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPage() {
        return view('attachment.image.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function ajaxDelete(Request $request) {
        $id = $request->get('id');

        return $this->basicAjaxFunction(['id' => $id],
            function ($parameter, $get) {
                try {
                    $this->repository->delete($get('id'));
                    return JSendResponse::success();
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(404, ['error' => 'File not found']);
                }
            }
        );
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
                        $whereCond = [['filename', 'like', "%" . $get('search') . "%"]];
                        $withCond = ['comment' => function ($query) {
                            $query->with('user');
                            $query->with('event');
                        }];
                        $files = $this->repository->loadTable($whereCond, $withCond, $get('sort'), $get('order'), $get('offset'), $get('limit'));
                        $data = ['total' => $this->repository->whereCount($whereCond), 'data' => $files];
                    } else {
                        $withCond = ['comment' => function ($query) {
                            $query->with('user');
                            $query->with('event');
                        }];
                        $files = $this->repository->loadTable1($withCond, $get('sort'), $get('order'), $get('offset'), $get('limit'));
                        $data = ['total' => $this->repository->count(), 'data' => $files];
                    }

                    return JSendResponse::success(200, $data);
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(400, ['error' => 'User not found']);
                }
            }
        );
    }

}