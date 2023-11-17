<?php

namespace SchedulingTerms\App\Models;

readonly class Term
{
    public function __construct(
        public int    $id,
        public Job    $job,
        public Company    $company,
        public User    $user,
        public string $startDate,
        public string $endDate
    )
    {
    }
}