<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Http\Validators;

use Rakit\Validation\Validation;
use Rakit\Validation\Validator;
use Slim\Http\ServerRequest;

abstract  class RequestValidator extends ServerRequest
{
    
    public abstract function rules(): array;
    public function validated(array $data): Validation {
        return (new Validator())->validate($data, $this->rules());
    }
}