<?php

namespace App\Http\Controllers\Admin;

use App\Components\AjaxHelper\ajaxHandler;
use App\Components\AjaxHelper\Contracts\AjaxContract;
use App\Events\UserDelete;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Roles\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JSendResponse;

/**
 * Class UserManagementController
 * @package App\Http\Controllers\Admin
 */
class UserManagementController extends Controller implements AjaxContract {
    use ajaxHandler;

    /**
     * UserManagementController constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userList() {
        $users = User::withCount('comments', 'feedback')->paginate(15);

        return view('role.user.index', compact('users'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function ajaxToggle(Request $request) {
        $uid = $request->get('uid');

        return $this->basicAjaxFunction(['uid' => $uid],
            function ($parameter, $get) {
                try {
                    $user = User::findOrFail($get('uid'));

                    if ($user->isNotBanned()) {
                        $user->bans()
                            ->create([
                                'comment' => 'banned by ' . Auth::user()->name . ' at ' . time()
                            ]);
                    } else {
                        $user->unban();
                    }

                    return JSendResponse::success();
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
    private function ajaxDelete(Request $request) {
        $uid = $request->get('uid');

        return $this->basicAjaxFunction(['uid' => $uid],
            function ($parameter, $get) {
                try {
                    $this->repository->deleteWithPreNotify($get('uid'));

                    return JSendResponse::success();
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
                        $user = User::withCount('comments')
                            ->where('username', 'like', "%" . $parameter['search'] . "%")
                            ->orderBy($get('sort'), $get('order'))
                            ->skip($get('offset'))
                            ->take($get('limit'))
                            ->get();

                        $user = ['total' => count($user), 'data' => $user];
                    } else {
                        $user = User::withCount('comments')
                            ->orderBy($get('sort'), $get('order'))
                            ->skip($get('offset'))
                            ->take($get('limit'))
                            ->get();

                        $user = ['total' => User::count(), 'data' => $user];
                    }

                    return JSendResponse::success(200, $user);
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(404, ['error' => 'User not found']);
                }
            }
        );
    }
}
