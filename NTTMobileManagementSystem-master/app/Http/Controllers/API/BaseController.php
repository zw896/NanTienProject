<?php

namespace App\Http\Controllers\API;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Class BaseController
 * @package App\Http\Controllers
 */
class BaseController extends Controller {
    use DispatchesJobs;

    protected $entityPerPage = 5;

    /**
     * Get the guard to be used by all sub-class
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard() {
        return Auth::guard('api');
    }

    /**
     * @param Request $request
     * @param int $default
     * @return int|mixed
     */
    protected function getPage(Request $request, $default = 1) {
        $page = $request->get('page', $default);

        if ($page < 1)
            $page = 1;

        return $page;
    }

    /**
     * @param $curPage
     * @param $total
     * @return bool
     */
    protected function hasNextPage($curPage, $total) {
        return ($curPage < ceil($total / $this->entityPerPage));
    }
}





