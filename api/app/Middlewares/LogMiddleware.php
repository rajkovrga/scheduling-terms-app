<?php

namespace SchedulingTerms\App\Middlewares;

class LogMiddleware
{
    public function __invoke($request, $response, $next)
    {
        return $next($request, $response);
    }
}