<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Exceptions;

use SchedulingTerms\App\Core\AppErrorHandler;

class Handler extends AppErrorHandler
{
    protected array $exceptions = [
        AuthException::class => 401,
    ];

}