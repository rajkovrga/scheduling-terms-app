<?php

namespace SchedulingTerms\App\Exceptions;
use JetBrains\PhpStorm\Pure;
use Throwable;

class ModelNotFoundException extends \Exception
{
    #[Pure] public function __construct($message = "Model not found", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}