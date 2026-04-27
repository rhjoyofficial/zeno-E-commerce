<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('getSettings')) {
    /**
     * Global helper to retrieve site settings with caching.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function getSettings($key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            try {
                return Setting::get($key, $default);
            } catch (\Exception $e) {
                return $default;
            }
        });
    }
}
