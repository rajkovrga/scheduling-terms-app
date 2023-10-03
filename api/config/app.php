<?php

declare(strict_types=1);

use SchedulingTerms\App\Utils\Config;

return [
    'environment' => Config::env('APP_ENVIRONMENT', 'production'),
    'Datasources' => [
        'default' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Postgres',
            'persistent' => false,
            'host' => Config::env('db_host') . ':' . Config::env('db_port'),
            'username' => Config::env('db_username'),
            'password' => Config::env('db_password'),
            'database' => Config::env('db_name'),
            'timezone' => Config::env('db_charset'),
            'cacheMetadata' => true,
            'quoteIdentifiers' => false,
        ],
    ],
];
