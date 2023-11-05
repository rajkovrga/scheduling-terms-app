<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Http\Validators\Users;

use SchedulingTerms\App\Http\Validators\RequestValidator;

class UserRequestValidator extends RequestValidator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'company_id' => 'required|numeric',
            'role_id' => 'required|numeric'
        ];
    }
}