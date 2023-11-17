<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Dto\Terms;

use Carbon\CarbonImmutable;
use SchedulingTerms\App\Dto\BaseDto;

class CreateUpdateTermDto extends BaseDto
{
    public ?CarbonImmutable $startDate;
    public function __construct(
        public readonly int $userId,
        public readonly int $jobId,
        public readonly int $companyId,
        string $startDate
    )
    {
        $this->startDate = $this->parseDateTime($startDate);
    }
}