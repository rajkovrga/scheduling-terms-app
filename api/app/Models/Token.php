<?php

namespace SchedulingTerms\App\Models;

class Token
{
    public function __construct(
        public int    $id,
        public User    $user,
        public string $token
    )
    {
    }
}