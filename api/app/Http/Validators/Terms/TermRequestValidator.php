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
            'name' => 'required|min:4',
            // TODO: FIX
            'start_date' => 'required|date|date_format:' . DateTimeInterface::ATOM,
            'end_date' => 'required|date|date_format:' . DateTimeInterface::ATOM,
            'job_id' => 'required|numeric'
        ];
    }
}