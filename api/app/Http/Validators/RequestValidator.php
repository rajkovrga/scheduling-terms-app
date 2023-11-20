<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Http\Validators;

use Rakit\Validation\RuleQuashException;
use Rakit\Validation\Validation;
use Rakit\Validation\Validator;
use SchedulingTerms\App\Http\Rules\PasswordRule;
use Slim\Http\ServerRequest;

abstract  class RequestValidator extends ServerRequest
{
    
    public abstract function rules(): array;

    /**
     * @throws RuleQuashException
     */
    public function validated(array $data): Validation {
        $validator = new Validator();

        $validator->addValidator('password', new PasswordRule);
        return $validator->validate($data, $this->rules());
    }
}