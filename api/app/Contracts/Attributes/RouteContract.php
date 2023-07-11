<?php

namespace SchedulingTerms\App\Contracts\Attributes;

interface RouteContract
{
    public function __construct(string $path, array $middlewares = [], bool $acceptGroupMiddlewares = true);

}