<?php

namespace SchedulingTerms\App\Http\Validators\Auth;

use SchedulingTerms\App\Http\Validators\RequestValidator;

class LoginValidator extends RequestValidator
{

    public function rules(): array
    {
        return [
          'email' => 'required|email',
          'password' => 'required|password'
        ];
    }
}