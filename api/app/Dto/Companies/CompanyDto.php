<?php

namespace SchedulingTerms\App\Dto\Companies;

use SchedulingTerms\App\Dto\BaseDto;

class CompanyDto extends BaseDto
{
    private int $id;
    private string $name;
    private string $createdAt;
    private string $updatedAt;
}