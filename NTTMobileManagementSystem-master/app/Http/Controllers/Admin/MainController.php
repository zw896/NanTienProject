<?php

namespace App\Http\Controllers\Admin;

use App\Components\AjaxHelper\ajaxHandler;
use App\Components\AjaxHelper\Contracts\AjaxContract;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Event;
use Illuminate\Http\Request;
use JSendResponse;

/**
 * Class MainController
 * @package App\Http\Controllers\Admin
 */
class MainController extends Controller implements AjaxContract {
    use ajaxHandler;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard() {
        $collects = (new \App\Components\Helpers\AdminDashboardHelper)->render();
        $period =
            [
                'start' => date("1 M, Y", strtotime('-6 Months')),
                'end' => date("d M, Y", time())
            ];

        return view('dashboard.index', ['collects' => $collects, 'period' => $period]);
    }

    private function ajaxReport(Request $request) {
        $result = call_user_func_array('array_merge_recursive', $this->generateMonthlyReport());

        return JSendResponse::success(200, $result);
    }

    /**
     * @return array
     */
    private function generateMonthlyReport() {
        $data = [];
        for ($i = 6; $i >= 0; --$i) {
            $times = $this->getMonthMinus("-$i months");

            $data[] = [
                'name' => $times['name'],
                'event' => $this->countEventByTimeStamp($times['first'], $times['last']),
                'comment' => $this->countCommentByTimeStamp($times['first'], $times['last'])
            ];
        }
        return $data;
    }

    /**
     * @param $time
     * @return array
     */
    private function getMonthMinus($time) {
        return [
            'name' => date("M", strtotime($time)),
            'first' => date("Y-m-01 00:00:00", strtotime($time)),
            'last' => date("Y-m-t 23:59:59", strtotime($time))
        ];
    }

    /**
     * @param $start
     * @param $end
     * @return mixed
     */
    private function countEventByTimeStamp($start, $end) {
        return Event::where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->count();
    }

    /**
     * @param $start
     * @param $end
     * @return mixed
     */
    private function countCommentByTimeStamp($start, $end) {
        return Comment::where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->count();
    }
}