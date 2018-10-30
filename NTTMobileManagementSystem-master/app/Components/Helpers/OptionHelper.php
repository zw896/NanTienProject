<?php

namespace App\Components\Helpers;

use App\Models\Option;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

/**
 * Class OptionHelper
 * @package App\Components\Helpers
 */
class OptionHelper {

    /**
     * @param $key
     * @return null
     */
    public static function get($key) {
        $value = Cache::remember(static::getCacheKey($key), 60 * 6, function () use ($key) {
            return Option::where('option_name', $key)->first();
        });

        return (!is_null($value)) ? $value->option_value : null;
    }

    /**
     * @param $key
     * @param $default
     * @return mixed
     */
    public static function getOrRemember($key, $default) {
        try {
            return Cache::remember(static::getCacheKey($key), 60 * 6, function () use ($key) {
                return Option::where('option_name', $key)->firstOrFail();
            });

        } catch (ModelNotFoundException $e) {
            static::set($key, $default);
            return $default;
        }
    }

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value) {
        // insert or update records
        Option::updateOrCreate(['option_name' => $key], ['option_value' => $value]);

        // clear cache
        Cache::forget(static::getCacheKey($key));
    }

    /**
     * @param $key
     * @return string
     */
    private static function getCacheKey($key) {
        return 'option_' . $key;
    }
}
