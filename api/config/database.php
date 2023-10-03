<?php

declare(strict_types=1);

use SchedulingTerms\App\Utils\Config;

return [
    'redis' => [
        'host' => Config::env('REDIS_HOST', '127.0.0.1'),
        'port' => (int)Config::env('REDIS_PORT', 6379),
    ],

    //    REDIS_HOST=
//    REDIS_PORT=
//
//    DB_PORT=5432
//DB_USERNAME=root
//DB_PASSWORD=root
//DB_HOST=localhost
//DB_NAME=termsDb
//DB_CHARSET=utf8

];