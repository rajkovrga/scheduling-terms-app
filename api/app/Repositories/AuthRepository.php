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
        $data = $this->connection->selectQuery([
            'permissions.name'
        ], 'permissions')
            ->innerJoin('role_permission', ['permissions.id = role_permission.permission_id'])
            ->where(['role_id' => $roleId])
            ->execute()
            ->fetchAll();
        
        return call_user_func_array('array_merge', $data);
    }

    public function saveRecoveryToken(string $token, int $userId): void
    {
    }
    

    public function checkRecoveryToken(string $token): ?int
    {
        return null;
    }
    
    public function changePassword(int $userId, string $password): void
    {
       $result = $this->connection
            ->updateQuery('users')
            ->set([
                'password' => $password
            ])
            ->where(['id' => $userId]);
    
        $result->execute();
    }

}