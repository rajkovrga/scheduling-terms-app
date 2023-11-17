<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

class AuthException extends Exception
{
    #[Pure] public function __construct(string $message = "User is not authorized", int $code = 401, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}