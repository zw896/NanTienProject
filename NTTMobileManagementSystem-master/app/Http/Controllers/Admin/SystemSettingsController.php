<?php

namespace App\Http\Controllers\Admin;

use App\Components\AjaxHelper\ajaxHandler;
use App\Components\AjaxHelper\Contracts\AjaxContract;
use App\Http\Controllers\Controller;
use App\Models\EventFieldDefinition;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use JSendResponse;

/**
 * Class AdminManagementController
 * @package App\Http\Controllers\Admin
 */
class SystemSettingsController extends Controller implements AjaxContract {
    use ajaxHandler;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFieldDefinition() {
        return view('settings.field.definition');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function ajaxGetTable(Request $request) {
        $fields = EventFieldDefinition::all();
        return JSendResponse::success(200, ['data' => $fields, 'total' => count($fields)]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private function ajaxEdit(Request $request) {
        $id = $request->get('id');
        $define = $request->get('define');

        return $this->basicAjaxFunction(['id' => $id, 'define' => $define],
            function ($parameter, $get) {
                try {
                    $field = EventFieldDefinition::findOrFail($get('id'));

                    // update title
                    $field->define = $get('define');
                    $field->save();
                    return JSendResponse::success();
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(404, ['error' => 'Field not found']);
                } catch (QueryException $e) {
                    return JSendResponse::error(500, ['error' => 'definition already exists.']);
                }
            }
        );
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
                    $feedback = EventFieldDefinition::findOrFail($get('id'));
                    $feedback->delete();

                    return JSendResponse::success();
                } catch (ModelNotFoundException $e) {
                    return JSendResponse::fail(404, ['error' => 'Field not found']);
                }
            }
        );
    }

}
