<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Http\Validators\Terms;

use DateTimeInterface;
use SchedulingTerms\App\Http\Validators\RequestValidator;

class TermRequestValidator extends RequestValidator
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|numeric',
            'start_date' => 'required|date:' . DateTimeInterface::ATOM,
            'job_id' => 'required|numeric'
        ];
    }
}