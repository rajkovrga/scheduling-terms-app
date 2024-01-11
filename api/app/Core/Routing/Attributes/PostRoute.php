<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Core\Routing\Attributes;

use Attribute;
use SchedulingTerms\App\Contracts\Attributes\RouteContract;
use SchedulingTerms\App\Enums\HttpMethod;

#[Attribute(Attribute::TARGET_METHOD)]
readonly class PostRoute extends Route
{
    public function __construct(string $path, array $middlewares = [], bool $skipGroupMiddleware = false)
    {
        parent::__construct(HttpMethod::POST, $path, $middlewares, $skipGroupMiddleware);
    }
}