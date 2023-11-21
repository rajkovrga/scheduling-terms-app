<?php

namespace SchedulingTerms\App\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

class TokenAuthException extends Exception
{
    #[Pure] public function __construct(string $message = "User is not logged", int $code = 401, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}