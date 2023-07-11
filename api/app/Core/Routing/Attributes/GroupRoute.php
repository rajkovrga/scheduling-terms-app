<?php

namespace SchedulingTerms\App\Core\Routing\Attributes;

use SchedulingTerms\App\Contracts\Attributes\RouteGroupContract;

#[\Attribute(\Attribute::TARGET_CLASS)]
class GroupRoute implements RouteGroupContract
{
    public function __construct(string $groupPath,array $middlewares = [])
    {
    }
}