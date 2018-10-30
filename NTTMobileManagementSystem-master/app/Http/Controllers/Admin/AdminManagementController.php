<?php

namespace App\Http\Controllers\Admin;

use App\Components\AjaxHelper\ajaxHandler;
use App\Components\AjaxHelper\Contracts\AjaxContract;
use App\Http\Controllers\Controller;
use App\Repositories\AdminRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JSendResponse;

/**
 * Class AdminManagementController
 * @package App\Http\Controllers\Admin
 */
class AdminManagementController extends Controller implements AjaxContract {
    use ajaxHandler;

    /**
     * AdminManagementController constructor.
     * @param AdminRepository $adminRepository
     */
    public function __construct(AdminRepository $adminRepository) {
        $this->repository = $adminRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminList() {
        return view('role.admin.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function ajaxNameUnique(Request $request) {
        $name = $request->get('name');

        return $this->basicAjaxFunction(['name' => $name],
            function ($parameter, $get) {
                $validator = $this->uniqueNameValidator($parameter);
                if ($validator->fails()) {
                    return JSendResponse::fail(400, ['error' => $validator->messages()]);
                } else {
                    return JSendResponse::success();
                }
            }
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function ajaxEdit(Request $request) {
        $name = $request->get('name', null);
        $pass = $request->get('password', null);
        $pass_c = $request->get('password_confirmation', null);
        $uid = $request->get('uid');

        if (!is_null($pass) && Hash::check($pass, $pass_c)) {
            return JSendResponse::fail(400, ['error' => 'Confirm password mismatch']);
        }

        if (!is_null($name)) {
            $validator = $this->uniqueNameValidator($request->all());
            if ($validator->fails()) {
                return JSendResponse::fail(400, ['error' => $validator->messages()]);
            }
        }

        try {
            $attribute = [];
            if (!is_null($pass)) {
                $attribute['password'] = Hash::make($pass);
            }

            if (!is_null($name)) {
                $attribute['name'] = $name;
            }

            $this->repository->update($attribute, $uid);

            return JSendResponse::success();

        } catch (ModelNotFoundException $e) {
            return JSendResponse::fail(404, ['error' => 'User not found']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function ajaxToggle(Request $request) {
        $uid = $request->get('uid');

        if ($this->isProtected($uid)) {
            return JSendResponse::fail(403, ['error' => 'You cannot ban this user']);
        } else {
            return $this->basicAjaxFunction(['uid' => $uid],
                function ($parameter, $get) {
                    try {
                        $admin = $this->repository->find($get('uid'));

                        if ($admin->isNotBanned()) {
                            $admin->bans()
                                ->create([
                                    'comment' => 'Banned by ' . Auth::user()->name . ' at ' . time()
                                ]);
                        } else {
                            $admin->unban();
                        }

                        return JSendResponse::success();
                    } catch (ModelNotFoundException $e) {
                        return JSendResponse::fail(404, ['error' => 'User not found']);
                    }
                }
            );
        }
    }

    /**
     * you cannot ban the last user and yourself
     * @return bool
     */
    private function isProtected($uid) {
        return ($this->repository->count() <= 1) || ($uid == Auth::id());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function ajaxDelete(Request $request) {
        $uid = $request->get('uid');
        if ($this->isProtected($uid)) {
            return JSendResponse::fail(403, ['error' => 'You cannot delete this user']);
        } else {
            return $this->basicAjaxFunction(['uid' => $uid],
                function ($parameter, $get) {
                    try {
                        $this->repository->delete($get('uid'));
                        return JSendResponse::success();
                    } catch (ModelNotFoundException $e) {
                        return JSendResponse::fail(404, ['error' => 'User not found']);
                    }
                }
            );
        }
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
                        $whereCond = [['name', 'like', "%" . $get('search') . "%"]];
                        $admin = $this->repository->loadTable($whereCond, null, $get('sort'), $get('order'), $get('offset'), $get('limit'));
                        $data = ['total' => $this->repository->whereCount($whereCond), 'data' => $admin];
                    } else {
                        $admin = $this->repository->loadTable1(null, $get('sort'), $get('order'), $get('offset'), $get('limit'));
                        $data = ['total' => $this->repository->count(), 'data' => $admin];
                    }

                    return JSendResponse::success(200, $data);
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(400, ['error' => 'User not found']);
                }
            }
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function ajaxRegister(Request $request) {
        return $this->basicAjaxFunction(['request' => $request],
            function ($parameter, $get) {
                // create an validator
                $validator = $this->validator($get('request')->all());

                if ($validator->fails()) {
                    return JSendResponse::fail(400, ['error' => $validator->messages()]);
                } else {
                    $user = $this->register($get('request')->all());
                    // push event
                    event(new Registered($user));

                    return JSendResponse::success();
                }
            }
        );
    }

    /**
     * @param array $data
     * @return \Illuminate\Validation\Validator
     */
    private function uniqueNameValidator(array $data) {
        return Validator::make($data, ['name' => 'required|max:255|unique:admins']);
    }

    /**
     * @param array $data
     * @return \Illuminate\Validation\Validator
     */
    private function validator(array $data) {
        return Validator::make($data, [
            'name' => 'required|max:255|unique:admins',
            'email' => 'required|email|max:255|unique:admins',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function register(array $data) {
        // create user
        return $this->repository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
