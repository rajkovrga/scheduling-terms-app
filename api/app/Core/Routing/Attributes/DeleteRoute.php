<?php

namespace SchedulingTerms\App\Core\Routing\Attributes;

use SchedulingTerms\App\Contracts\Attributes\RouteContract;

#[\Attribute(\Attribute::TARGET_CLASS)]
class DeleteRoute implements RouteContract
{
    public function __construct(string $path, array $middlewares = [])
    {
    }
}