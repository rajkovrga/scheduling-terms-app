<?php

namespace SchedulingTerms\App\Models;

class Term
{
    public function __construct(
        public readonly int $id,
        public readonly int $jobId,
        public readonly int $companyId,
        public readonly int $userId,
        public readonly string $startDate,
        public readonly string $endDate
    )
    {
    }
}