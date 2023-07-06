<?php

namespace SchedulingTerms\App\Contracts\Attributes;

interface RouteGroupContract
{
    public function __construct(string $group, array $middlewares = []);
}