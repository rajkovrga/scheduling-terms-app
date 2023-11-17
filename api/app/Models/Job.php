<?php

namespace SchedulingTerms\App\Models;

readonly class Job
{
    public function __construct(
        public int     $id,
        public string  $name,
        public int     $during,
        public ?Company     $company,
        public string  $createdAt,
        public ?string $updatedAt
    )
    {
    }
}