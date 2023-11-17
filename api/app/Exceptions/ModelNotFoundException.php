<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Exceptions;
use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

class ModelNotFoundException extends Exception
{
    #[Pure] public function __construct($message = "Model not found", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}