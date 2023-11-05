<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Dto\Companies;

use Carbon\CarbonImmutable;
use SchedulingTerms\App\Dto\BaseDto;

class CreateUpdateCompanyDto extends BaseDto
{
    public function __construct(
        public readonly string $name
    )
    {
    }
}