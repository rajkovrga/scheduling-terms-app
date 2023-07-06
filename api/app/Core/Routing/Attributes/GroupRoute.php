<?php

namespace SchedulingTerms\App\Core\Routing\Attributes;
use Attribute;
use SchedulingTerms\App\Contracts\Attributes\RouteGroupContract;

#[Attribute(\Attribute::TARGET_CLASS)]
class GroupRoute implements RouteGroupContract
{
    public function __construct(string $group, array $middlewares = [])
    {
    }
}