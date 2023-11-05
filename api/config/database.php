<?php
declare(strict_types=1);

use Cake\Database\Connection;
use Cake\Database\Driver\Postgres;
use SchedulingTerms\App\Utils\Config;

return [
    'redis' => [
        'host' => Config::env('REDIS_HOST', '127.0.0.1'),
        'port' => (int)Config::env('REDIS_PORT', 6379),
        'connectTimeout' => (float)Config::env('REDIS_CONNECTION_TIMEOUT', 6379),
        'backoff' => [
            'algorithm' => Redis::BACKOFF_ALGORITHM_DECORRELATED_JITTER,
            'base' => 500,
            'cap' => 750,
        ],
        'options' => [
            Redis::OPT_SERIALIZER => Redis::SERIALIZER_JSON,
            Redis::OPT_TCP_KEEPALIVE => true,
            Redis::OPT_PREFIX => Config::env('REDIS_PREFIX', 6379),
            Redis::OPT_COMPRESSION => Redis::COMPRESSION_ZSTD,
            Redis::OPT_COMPRESSION_LEVEL => (int)Config::env('REDIS_COMPRESSION_LEVEL', 6379),
            Redis::OPT_MAX_RETRIES => (int)Config::env('REDIS_MAX_RETRIES', 6379),
            Redis::OPT_SCAN => Redis::SCAN_PREFIX
        ]
    ],

    'pgsql' => [
        'className' => Connection::class,
        'driver' => Postgres::class,
        'persistent' => true,
        'host' => Config::env('DB_HOST'),
        'port' =>  (int)Config::env('DB_PORT'),
        'username' => Config::env('DB_USERNAME'),
        'password' => Config::env('DB_PASSWORD'),
        'database' => Config::env('DB_NAME'),
        'timezone' => Config::env('DB_TIMEZONE', 'UTC'),
        'cacheMetadata' => true,
        'quoteIdentifiers' => true,
        'encoding' => 'UTF-8',
        'schema' => 'public',
        'flags' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    ],
];