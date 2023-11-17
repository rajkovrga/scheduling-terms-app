<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

class MiddlewareException extends Exception
{

    #[Pure] public function __construct($message = "Middleware not found in Kernel", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}