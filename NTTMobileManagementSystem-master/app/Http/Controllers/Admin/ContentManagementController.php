<?php

namespace App\Http\Controllers\Admin;

use App\Components\AjaxHelper\ajaxHandler;
use App\Components\AjaxHelper\Contracts\AjaxContract;
use App\Http\Controllers\Controller;
use App\Jobs\SendNotificationToApp;
use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use JSendResponse;

/**
 * Class ContentManagementController
 * @package App\Http\Controllers\Admin
 */
class ContentManagementController extends Controller implements AjaxContract {
    use ajaxHandler;

    /**
     * ContentManagementController constructor.
     * @param EventRepository $repository
     */
    public function __construct(EventRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * this method will return 15 records of event per page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEventList() {
        return view('content.event.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEvent($id) {
        $event = $this->repository->find($id);

        return view('content.event.edit', compact('event'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveEvent(Request $request, $id) {
        $this->validate($request, [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        $attribute = ['title' => $request->title, 'body' => $request->body];
        $this->repository->update($attribute, $id);

        return redirect('/admin/content/event');
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
                    $this->repository->deleteWithPreNotify($get('id'));

                    return JSendResponse::success();
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(404, ['error' => 'Event not found']);
                }
            }
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function ajaxToggle(Request $request) {
        $id = $request->get('id');

        return $this->basicAjaxFunction(['id' => $id],
            function ($parameter, $get) {
                try {
                    $event = $this->repository->find($get('id'));
                    $event->published = (!$event->published);
                    $event->save();

                    // async job
                    if (!$event->pushed && !$event->published) {
                        dispatch(new SendNotificationToApp($event));
                    }

                    return JSendResponse::success();
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(404, ['error' => 'Event not found']);
                }
            }
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function ajaxEdit(Request $request) {
        $id = $request->get('id');
        $title = $request->get('title');
        $sticky = $request->get('sticky');
        $priority = $request->get('priority');
        $featured = $request->get('featured');

        return $this->basicAjaxFunction(['id' => $id, 'title' => $title, 'sticky' => $sticky, 'priority' => $priority, 'featured' => $featured],
            function ($parameter, $get) {
                try {
                    $attribute = [
                        'title' => $get('title'),
                        'sticky' => $get('sticky'),
                        'priority' => $get('priority'),
                        'featured' => $get('featured')
                    ];

                    $this->repository->update($attribute, $get('id'));

                    return JSendResponse::success();
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(404, ['error' => 'Event not found']);
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
                        $events = Event::withCount('comments')
                            ->where('title', 'like', "%" . $parameter['search'] . "%")
                            ->orderBy('sticky', 'desc')
                            ->orderBy('priority', 'desc')
                            ->orderBy($get('sort'), $get('order'))
                            ->skip($get('offset'))
                            ->take($get('limit'))
                            ->get();

                        $total = Event::where('title', 'like', "%" . $parameter['search'] . "%")
                            ->count();

                        $events = ['total' => $total, 'data' => $events];
                    } else {
                        $events = Event::withCount('comments')
                            ->orderBy('sticky', 'desc')
                            ->orderBy('priority', 'desc')
                            ->orderBy($get('sort'), $get('order'))
                            ->skip($get('offset'))
                            ->take($get('limit'))
                            ->get();

                        $events = ['total' => Event::count(), 'data' => $events];
                    }

                    return JSendResponse::success(200, $events);
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(404, ['Error' => 'Event not found']);
                }
            }
        );
    }
}
