<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Enums;

enum HttpMethod: string
{
    case GET = 'get';
    case POST = 'post';
    case PUT = 'put';
    case DELETE = 'delete';
}
