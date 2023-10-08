<?php

declare(strict_types=1);

namespace SchedulingTerms\App\Core\Routing\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
readonly class DeleteRoute extends Route
{
    public function __construct(string $path, array $middlewares = [], bool $acceptGroupMiddlewares = true)
    {
        parent::__construct(HttpMethod::DELETE, $path, $middlewares, $acceptGroupMiddlewares);
    }
}