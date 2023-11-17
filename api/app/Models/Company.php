<?php

namespace SchedulingTerms\App\Models;

readonly class Company
{
    public function __construct(
        public ?int     $id,
        public ?string  $name,
        public ?string  $createdAt,
        public ?string $updatedAt
    )
    {
    }
}