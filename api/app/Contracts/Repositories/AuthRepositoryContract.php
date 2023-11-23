<?php

namespace SchedulingTerms\App\Contracts\Repositories;

interface AuthRepositoryContract
{
    /**
     * @return array<string>
     */
    public function getPermissions($roleId): array;
    public function saveRecoveryToken(string $token, int $userId): void;
    public function checkRecoveryToken(string $token): ?int;
    public function changePassword(int $userId, string $password): void;
}