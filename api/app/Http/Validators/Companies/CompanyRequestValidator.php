<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Http\Validators\Companies;

use SchedulingTerms\App\Http\Validators\RequestValidator;

class CompanyRequestValidator extends RequestValidator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required'
        ];
    }
}