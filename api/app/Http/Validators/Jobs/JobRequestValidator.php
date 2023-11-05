<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Http\Validators\Jobs;

use SchedulingTerms\App\Http\Validators\RequestValidator;

class JobRequestValidator extends RequestValidator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'during' => 'required|numeric',
            'name' => 'required|min:4'
        ];
    }
}