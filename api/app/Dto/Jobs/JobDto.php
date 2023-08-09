<?php

namespace SchedulingTerms\App\Dto\Jobs;

use SchedulingTerms\App\Dto\BaseDto;

class JobDto extends BaseDto
{
    private int $id;
    private string $name;
    private int $during;
    private $company;
    private string $createdAt;
    private string $updatedAt;

}