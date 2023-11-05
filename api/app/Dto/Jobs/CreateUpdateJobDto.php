<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Dto\Jobs;

use Carbon\CarbonImmutable;
use SchedulingTerms\App\Dto\BaseDto;

class CreateUpdateJobDto extends BaseDto
{
    public function __construct(
        public string $name,
        public int $during,
        public int $companyId
    )
    {
    }
}


