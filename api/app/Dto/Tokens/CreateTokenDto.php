<?php

namespace SchedulingTerms\App\Dto\Tokens;

class CreateTokenDto
{
    public function __construct(
        public readonly int $userId,
        public string $token
    )
    {
    }
}