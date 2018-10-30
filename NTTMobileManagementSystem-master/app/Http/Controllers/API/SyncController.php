<?php

namespace App\Http\Controllers\API;

use App\Models\Event;
use App\Models\EventField;
use App\Models\EventFieldDefinition;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use JSendResponse;

/**
 * Class SyncController
 *
 * @package App\Http\Controllers
 */
class SyncController extends BaseController {
    private $s_key;
    private $s_iv;
    private $s_cipher;
    /**
     * SyncController constructor.
     */
    public function __construct() {
        $this->s_key = config('app.s_key');
        $this->s_iv = config("app.s_iv");
        $this->s_cipher = config("app.s_cipher");

        if ($this->s_key == "" or $this->s_iv == "") {
            Log::error("unable to find key or iv");
            abort(500);
        }
    }

    /**
     * this method will extract data from user inout
     * if we successfully decrypt the message, then this method
     * will return an array with message => ok and code => 200
     * otherwise return message => unknown message and code => 500
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ping(Request $request) {
        // fetch the message from $_POST
        $data = $request->get("message");

        // trying to decrypt the message
        $decrypted = $this->decrypt($data);

        // return as json format
        if ($decrypted == "ping") {
            return JSendResponse::success(200, ['message' => 'pong']);
        } else {
            return JSendResponse::error(500);
        }
    }

    /**
     * this method read data from user input
     * if data section contain a valid json format
     * data, then add to the database
     * otherwise return an error message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request) {
        // read data
        $data = $request->get('data');

        // trying to decrypt the message
        $decrypted = $this->decrypt($data);

        if (($json = json_decode($decrypted)) == null && json_last_error() != JSON_ERROR_NONE) {
            return JSendResponse::error(500, ['error' => 'Cannot decrypt message, check key and iv settings.']);
        } else {
            // if not found, then insert new
            try {
                Event::where('eid', '=', $json->id)
                    ->firstOrFail();

                return JSendResponse::success();
            } catch (ModelNotFoundException $exception) {
                // use transaction
                DB::transaction(function () use ($json) {
                    $event = Event::create([
                        'eid' => $json->id, 'type' => $json->type,
                        'title' => $json->title, 'body' => $json->body[0],
                        'author' => $json->owner, 'featured' => false,
                        'view' => 0, 'sticky' => 0, 'pushed' => false,
                        'priority' => 0, 'published' => false]);

                    // process field
                    foreach ($json->fields as $field) {
                        $fieldName = $field[0];

                        // field may be multi-value
                        foreach ($field[1] as $value) {
                            // if the value is not empty
                            if (isset($value->value)) {
                                EventField::create(['eid' => $event->getKey(), 'field_value' => $value->value, 'field_define' => $this->addOrGetFieldDefine($fieldName)->id]);
                            }
                        }
                    }
                });

                return JSendResponse::success(201);
            }
        }
    }

    /**
     * @param $name
     * @return EventFieldDefinition|\Illuminate\Database\Eloquent\Model
     */
    private function addOrGetFieldDefine($name) {
        try {
            // if found return the model
            return EventFieldDefinition::where('field_name', '=', $name)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            // if not found, create a new model instant
            return EventFieldDefinition::create(['field_name' => $name, 'define' => null]);
        }
    }

    /**
     * @param $message
     * @return string
     */
    private function decrypt($message) {
        // using the key to decrypt message
        return openssl_decrypt($message, $this->s_cipher, $this->s_key, null, $this->s_iv);
    }
}
