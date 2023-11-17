<?php

namespace SchedulingTerms\App\Helpers;

use Psr\SimpleCache\CacheInterface;
use Redis;
use RedisException;

class Cache implements CacheInterface
{
    
    public function __construct(
        private Redis $redis
    )
    {
    }
    
    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null)
    {
        // TODO: Implement get() method.
    }
    
    /**
     * @param string $key
     * @param mixed $value
     * @param int|\DateInterval|null $ttl
     * @return bool
     */
    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null)
    {
        // TODO: Implement set() method.
    }
    
    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key)
    {
        // TODO: Implement delete() method.
    }
    
    /**
     * @return bool
     */
    public function clear()
    {
        // TODO: Implement clear() method.
    }
    
    /**
     * @param iterable $keys
     * @param mixed|null $default
     * @return mixed[]
     */
    public function getMultiple(iterable $keys, mixed $default = null)
    {
        // TODO: Implement getMultiple() method.
    }
    
    /**
     * @param iterable $values
     * @param int|\DateInterval|null $ttl
     * @return bool
     */
    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null)
    {
        // TODO: Implement setMultiple() method.
    }
    
    /**
     * @param iterable $keys
     * @return bool
     * @throws RedisException
     */
    public function deleteMultiple(iterable $keys): bool
    {
        $keys = iterator_to_array($keys);
        return (bool)$this->redis->del($keys);
    }
    
    /**
     * @param string $key
     * @return bool
     * @throws RedisException
     */
    public function has(string $key): bool
    {
        return $this->redis->get($key) !== false;
    }
}