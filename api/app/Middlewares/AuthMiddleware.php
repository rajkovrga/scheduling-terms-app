<?php

namespace SchedulingTerms\App\Middlewares;

class AuthMiddleware
{
    public function __invoke($request, $response, $next)
    {
        return $next($request, $response);
    }
}