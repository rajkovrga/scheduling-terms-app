<?php

namespace SchedulingTerms\App\Models;

class User
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly int $companyId,
        public readonly int $roleId,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    )
    {
    }
}