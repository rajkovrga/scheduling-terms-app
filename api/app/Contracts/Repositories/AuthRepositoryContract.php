<?php

namespace SchedulingTerms\App\Contracts\Repositories;

interface AuthRepositoryContract
{
    /**
     * @return array<string>
     */
    public function getPermissions($roleId): array;
}