<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Core\Routing\Attributes;

use SchedulingTerms\App\Enums\HttpMethod;

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