<?php

namespace SchedulingTerms\App\Dto\Terms;

use SchedulingTerms\App\Dto\BaseDto;
use SchedulingTerms\App\Dto\Companies\CompanyDto;
use SchedulingTerms\App\Dto\Jobs\JobDto;
use SchedulingTerms\App\Dto\Users\UserDto;

class TermDto
{
    private int $id;
    private ?UserDto $user = null;
    private string $startDate;
    private string $endDate;
    private ?JobDto $job = null;
    private ?CompanyDto $company = null;

    public static function from(array $arr): static
    {

        return new self;
    }

}