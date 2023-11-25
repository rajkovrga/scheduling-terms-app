<?php

namespace SchedulingTerms\App\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

class PermissionDeniedException extends Exception
{
    #[Pure] public function __construct(string $message = "PermissionDenied", int $code = 403, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}