<?php

declare(strict_types=1);

namespace SchedulingTerms\App;

use SchedulingTerms\App\Middlewares\AuthMiddleware;

class Kernel extends Core\Kernel
{
    protected static array $middlewares = [
        'auth' => AuthMiddleware::class
    ];

    protected static array $globalMiddlewares = [];
}