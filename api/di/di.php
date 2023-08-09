<?php

use DI\Container;
use SchedulingTerms\App\Core\Data\DatabaseConnection;
use SchedulingTerms\App\Core\Data\RedisConnection;
use function DI\create;

return [
    RedisConnection::class => new RedisConnection($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']),
    DatabaseConnection::class => new DatabaseConnection($_ENV['DB_HOST'], $_ENV['DB_PORT'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME'], $_ENV['DB_CHARSET']),
];