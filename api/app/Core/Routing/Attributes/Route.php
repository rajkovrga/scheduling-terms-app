<?php

declare(strict_types=1);

namespace SchedulingTerms\App\Core\Routing\Attributes;

readonly class Route
{
    public function __construct(
        public HttpMethod $method,
        public string $path,
        public array $middlewares = [],
        public bool $acceptGroupMiddlewares = true
    ) {
    }
}