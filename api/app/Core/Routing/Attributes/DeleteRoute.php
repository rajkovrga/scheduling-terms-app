<?php

namespace SchedulingTerms\App\Core\Routing\Attributes;

use Attribute;
use SchedulingTerms\App\Contracts\Attributes\RouteContract;

#[Attribute(Attribute::TARGET_METHOD)]
class DeleteRoute implements RouteContract
{
    public function __construct(string $path, array $middlewares = [], bool $acceptGroupMiddlewares = true)
    {
    }
}