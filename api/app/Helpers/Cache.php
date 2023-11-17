<?php

namespace SchedulingTerms\App\Helpers;

use Carbon\CarbonInterval;
use DateInterval;
use Psr\SimpleCache\CacheInterface;
use Redis;
use RedisException;

class Cache implements CacheInterface
{
    public function __construct(
        private readonly Redis   $redis,
        protected CarbonInterval $cacheDuration,
        protected string         $cachePrefix,
    )
    {
    }

    /**
     * @throws RedisException
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = $this->getCacheKey($key);
        $value = $this->redis->get($cacheKey);

        if ($value === null) {
            return $default;
        }

        return $value;
    }

    /**
     * @throws RedisException
     */
    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        $cacheKey = $this->getCacheKey($key);
        return $this->redis->setex($cacheKey, $this->cacheDuration->totalSeconds, $value) == 'OK';
    }

    /**
     * @throws RedisException
     */
    public function delete(string $key): void
    {
        $cacheKey = $this->getCacheKey($key);
        $this->redis->del($cacheKey);
    }

    /**
     * @throws RedisException
     */
    public function clear(): void
    {
        $iterator = null;
        $keys = [];

        do {
            [$iterator, $currentKeys] = $this->redis->scan($iterator, ['MATCH' => $this->cachePrefix . '.*']);
            $keys = array_merge($keys, $currentKeys);
        } while ($iterator !== 0);

        if (!empty($keys)) {
            $this->redis->del($keys);
        }
    }

    /**
     * @throws RedisException
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $results = [];

        foreach ($keys as $key) {
            $cacheKey = $this->getCacheKey($key);
            $value = $this->redis->get($cacheKey);

            if ($value === null) {
                $results[$key] = $default;
            } else {
                $results[$key] = $value;
            }
        }

        return $results;
    }

    /**
     * @throws RedisException
     */
    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        $success = true;

        foreach ($values as $key => $value) {
            $cacheKey = $this->getCacheKey($key);

            if (!$this->set($cacheKey, $value, $ttl)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @throws RedisException
     */
    public function deleteMultiple(iterable $keys): bool
    {
        $success = true;

        foreach ($keys as $key) {
            $cacheKey = $this->getCacheKey($key);

            if (!$this->redis->del($cacheKey)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @throws RedisException
     */
    public function has(string $key): bool
    {
        $cacheKey = $this->getCacheKey($key);
        return $this->redis->exists($cacheKey);
    }


    private function getCacheKey(string $key): string
    {
        return $this->cachePrefix . $key;
    }

}