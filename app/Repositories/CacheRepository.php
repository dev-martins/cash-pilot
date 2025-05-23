<?php

namespace App\Repositories;

use Illuminate\Contracts\Cache\Repository as Cache;

class CacheRepository
{
    public function __construct(protected Cache $cache)
    {
    }

    public function get(string $key)
    {
        return $this->cache->get($key);
    }

    public function put(string $key, mixed $value, ?int $minutes)
    {
        $time = is_null($minutes) ? config('env.CACHE_TIME_IN_MINUTES') : $minutes;
        return $this->cache->put($key, $value, $time);
    }

    public function forget(string $key)
    {
        return $this->cache->forget($key);
    }

    public function has(string $key)
    {
        return $this->cache->has($key);
    }
}
