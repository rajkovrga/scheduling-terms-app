<?php

namespace SchedulingTerms\App\Http\Validators\Auth;

use SchedulingTerms\App\Http\Validators\RequestValidator;

class ChangePasswordValidator extends RequestValidator
{
    
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => 'required|password',
            'confirm_password' => 'required|same:password'
        ];
    }
}