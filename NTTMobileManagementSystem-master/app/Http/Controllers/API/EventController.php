<?php

namespace App\Http\Controllers\API;

use App\Models\Event;
use App\Models\EventField;
use App\Models\EventFieldDefinition;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use JSendResponse;

/**
 * Class EventController
 * @package App\Http\Controllers
 */
class EventController extends BaseController {
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEventPage(Request $request) {
        $page = $this->getPage($request);

        $queryBuilder = Event::where('published', '=', '1');
        $total = $queryBuilder->count();

        $events = $queryBuilder->limit($this->entityPerPage)
            ->with('fields')
            ->offset($this->entityPerPage * ($page - 1))
            ->orderBy('id', 'desc')
            ->get();

        return $events->isNotEmpty() ? JSendResponse::success(200, $this->buildData($events, $page, $total)) : JSendResponse::fail(404);
    }

    /**
     * @param Collection $events
     * @param $page
     * @param $total
     * @return array
     */
    private function buildData(Collection $events, $page, $total) {
        $data = [];
        foreach ($events as $event) {
            $data['events'][] =
                [
                    'id' => $event->id,
                    'type' => $event->type,
                    'title' => $event->title,
                    'summary' => $event->summary,
                    'sticky' => $event->sticky,
                    'fields' =>
                        [
                            $event->fields,
                        ]
                ];
        }

        $data['hasNext'] = $this->hasNextPage($page, $total);
        $data['pageNum'] = $page;

        return $data;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPopularPage(Request $request) {
        $page = $this->getPage($request);

        $queryBuilder = Event::where('featured', '=', '0')
            ->where('published', '=', '1');

        $total = $queryBuilder->count();

        $events = $queryBuilder
            ->limit($this->entityPerPage)
            ->with('fields')
            ->offset($this->entityPerPage * ($page - 1))
            ->orderBy('sticky', 'desc')
            ->orderBy('priority', 'desc')
            ->get();

        return $events->isNotEmpty() ? JSendResponse::success(200, $this->buildData($events, $page, $total)) : JSendResponse::fail(404);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFeaturedPage(Request $request) {
        $page = $this->getPage($request);

        $queryBuilder = Event::where('featured', '=', '1')
            ->where('published', '=', '1');

        $total = $queryBuilder->count();

        $events = Event::limit($this->entityPerPage)
            ->with('fields')
            ->where('published', '=', '1')
            ->where('featured', '=', '1')
            ->offset($this->entityPerPage * ($page - 1))
            ->orderBy('sticky', 'desc')
            ->orderBy('priority', 'desc')
            ->get();

        return $events->isNotEmpty() ? JSendResponse::success(200, $this->buildData($events, $page, $total)) : JSendResponse::fail(404);
    }

    /**
     * @param $year
     * @param null $month
     * @param null $day
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEventByDategetEventByDate($year, $month = null, $day = null) {
        if (!is_null($year)) {
            $start_time = Carbon::create($year, is_null($month) ? 01 : $month, is_null($day) ? 01 : $day, 00, 00, 00);
            $end_time = Carbon::create($year, is_null($month) ? 12 : $month, is_null($day) ? 31 : $day, 23, 59, 59);

            $event = Event::where('published', '=', '1')
                ->whereBetween('created_at', [$start_time, $end_time])
                ->get();

            if ($event->isEmpty())
                $event = null;

            return JSendResponse::success(200, $this->buildCalendar($event));
        }

        return JSendResponse::fail(400, ['error' => 'Year cannot be empty']);
    }

    /**
     * @param Collection $events
     * @return array
     */
    private function buildCalendar(Collection $events) {
        $data = [];

        foreach ($events as $event) {
            $time_field = $this->getTimeOfEvent($event->id);

            $data['events'][] =
                [
                    'id' => $event->id,
                    'type' => $event->type,
                    'title' => $event->title,
                    'start' => $time_field['start'],
                    'end' => $time_field['end'],
                ];
        }

        return $data;
    }

    /**
     * @param $id
     * @return array
     */
    private function getTimeOfEvent($id) {
        $result = [];
        try {
            $field_start = EventFieldDefinition::where('define', '=', 'StartDate')
                ->firstOrFail();

            $field_end = EventFieldDefinition::where('define', '=', 'EndDate')
                ->firstOrFail();

            $result['start'] = EventField::where('eid', '=', $id)
                ->where('field_define', '=', $field_start->id)
                ->firstOrFail()
                ->field_value;


            $result['end'] = EventField::where('eid', '=', $id)
                ->where('field_define', '=', $field_end->id)
                ->firstOrFail()
                ->field_value;

        } catch (ModelNotFoundException $e) {
            $result['start'] = null;
            $result['end'] = null;
        }

        return $result;
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEvent($id, Request $request) {
        try {
            $event = Event::where('id', '=', $id)
                ->where('published', '=', '1')
                ->with('fields')
                ->firstOrFail();

            // increase view counter, cache a dau
            $view_cache_key = 'view_' . $id . '_' . $request->ip();
            Cache::remember($view_cache_key, 86400, function () use ($id) {
                $e = Event::where('id', '=', $id)
                    ->where('published', '=', '1')
                    ->firstOrFail();
                ++$e->view;
                $e->save();

                return $e->view;
            });

            return JSendResponse::success(200, ['event' => $event]);
        } catch (ModelNotFoundException $e) {
            return JSendResponse::fail(404, ['error' => 'Event not found']);
        }
    }
}
