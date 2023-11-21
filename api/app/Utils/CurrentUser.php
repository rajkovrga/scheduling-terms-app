<?php


namespace SchedulingTerms\App\Utils;

use SchedulingTerms\App\Models\Company;
use SchedulingTerms\App\Models\User;

class CurrentUser
{
    public function __construct(
        public User $user,
        public array $permissions
    )
    {
    }
}