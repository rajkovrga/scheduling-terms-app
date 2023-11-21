<?php

namespace SchedulingTerms\App\Repositories;

use Cake\Database\Connection;
use SchedulingTerms\App\Contracts\Repositories\AuthRepositoryContract;

readonly class AuthRepository implements AuthRepositoryContract
{
    public function __construct(
        private Connection $connection
    )
    {
    }

    public function getPermissions($roleId): array
    {
        return $this->connection->selectQuery([
            'permissions.name'
        ],'permissions')
            ->innerJoin('role_permissions', ['permissions.id = role_permissions.permission_id'])
            ->where('role_id', $roleId)
            ->execute()
            ->fetchAll('array');
    }
}