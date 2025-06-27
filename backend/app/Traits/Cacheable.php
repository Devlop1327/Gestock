<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait Cacheable
{
    protected function getCached($key, $minutes, $callback)
    {
        return Cache::remember($key, $minutes * 60, $callback);
    }

    protected function forgetCache($key)
    {
        Cache::forget($key);
    }

    protected function getCacheKey($prefix, $params = [])
    {
        return $prefix . ':' . implode(':', $params);
    }
} 