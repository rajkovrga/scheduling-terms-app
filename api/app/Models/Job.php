<?php

namespace SchedulingTerms\App\Models;

class Job
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $during,
        public readonly int $companyId,
        public readonly string $createdAt,
        public readonly string $updateAt
    )
    {
    }
}