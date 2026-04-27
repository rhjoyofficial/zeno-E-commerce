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
        return Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
            try {
                return Setting::get($key, $default);
            } catch (\Exception $e) {
                // Return default if table doesn't exist yet or other error
                return $default;
            }
        });
    }
}
