<?php

namespace SchedulingTerms\App\Models;

readonly class User
{
    public function __construct(
        public int     $id,
        public string  $email,
        public string  $password,
        public ?Company     $company,
        public int     $roleId,
        public string  $createdAt,
        public ?string $updatedAt,
    )
    {
    }
}