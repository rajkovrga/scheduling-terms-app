<?php

declare(strict_types=1);

use DI\Container;
use SchedulingTerms\App\Contracts\Repositories\JobRepositoryContract;
use SchedulingTerms\App\Controllers\JobController;
use SchedulingTerms\App\Core\Data\DatabaseConnection;
use SchedulingTerms\App\Core\Data\RedisConnection;
use SchedulingTerms\App\Repositories\JobRepository;

return [
//    RedisConnection::class => new RedisConnection($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']),
//    DatabaseConnection::class => new DatabaseConnection($_ENV['DB_HOST'], $_ENV['DB_PORT'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME'], $_ENV['DB_CHARSET']),
//    JobRepositoryContract::class => static function(Container $container) {
//        return new JobRepository($container->get(DatabaseConnection::class));
//    },
//    JobController::class => static function(Container $container) {
//        return new JobController($container->get(JobRepositoryContract::class));
//    },
];