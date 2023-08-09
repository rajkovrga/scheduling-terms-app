<?php

namespace SchedulingTerms\App\Core\Data;

use Redis;
use RedisException;

class RedisConnection {
    private Redis $redis;
    public function __construct($host, $port) {
        $this->redis = new Redis();
        $this->connect($host, $port);
    }

    private function connect($host, $port): void
    {
        try {
            $this->redis->connect($host, $port);
            echo "Connected to Redis server." . PHP_EOL;
        } catch (RedisException $e) {
            echo "Error connecting to Redis server: " . $e->getMessage() . PHP_EOL;
        }
    }

    public function set($key, $value): void
    {
        try {
            $this->redis->set($key, $value);
            echo "Value set successfully." . PHP_EOL;
        } catch (RedisException $e) {
            echo "Error setting value: " . $e->getMessage() . PHP_EOL;
        }
    }

    public function get($key) {
        try {
            return $this->redis->get($key);
        } catch (RedisException $e) {
            echo "Error getting value: " . $e->getMessage() . PHP_EOL;
            return null;
        }
    }

    /**
     * @throws RedisException
     */
    public function close(): void {
        $this->redis->close();
        echo "Connection to Redis closed." . PHP_EOL;
    }
}