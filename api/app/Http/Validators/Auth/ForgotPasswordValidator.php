<?php

namespace SchedulingTerms\App\Http\Validators\Auth;

use SchedulingTerms\App\Http\Validators\RequestValidator;

class ForgotPasswordValidator extends RequestValidator
{
    
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email'
        ];
    }
}