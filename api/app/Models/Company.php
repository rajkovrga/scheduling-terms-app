<?php

namespace SchedulingTerms\App\Models;

class Company
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $createdAt,
        public readonly string $updatedAt
    )
    {
    }
}