<?php

declare(strict_types=1);

namespace SchedulingTerms\App\Core\Routing\Attributes;

use Attribute;
use SchedulingTerms\App\Contracts\Attributes\RouteContract;

#[Attribute(Attribute::TARGET_METHOD)]
readonly class PostRoute extends Route
{
    public function __construct(string $path, array $middlewares = [], bool $acceptGroupMiddlewares = true)
    {
        parent::__construct(HttpMethod::PUT, $path, $middlewares, $acceptGroupMiddlewares);
    }
}