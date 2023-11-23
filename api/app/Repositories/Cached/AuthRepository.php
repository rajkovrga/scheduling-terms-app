<?php

namespace SchedulingTerms\App\Repositories\Cached;

use RedisException;
use SchedulingTerms\App\Contracts\Repositories\AuthRepositoryContract;
use SchedulingTerms\App\Helpers\Cache;

readonly class AuthRepository implements AuthRepositoryContract
{
    public function __construct(
        private AuthRepositoryContract $authRepository,
        private Cache                  $roleCache,
        private Cache                  $recoveryCache
    )
    {
    }
    
    /**
     * @throws RedisException
     */
    public function getPermissions($roleId): array
    {
        $permissions = $this->roleCache->get($roleId);
        
        if (empty($permissions)) {
            $permissions = $this->authRepository->getPermissions($roleId);
            $this->roleCache->set($roleId, $permissions);
        }
        
        return $permissions;
    }
    
    /**
     * @throws RedisException
     */
    public function saveRecoveryToken(string $token, int $userId): void
    {
        $this->recoveryCache->set($token, $userId);
    }
    
    /**
     * @throws RedisException
     */
    public function checkRecoveryToken(string $token): ?int
    {
        return $this->recoveryCache->get($token);
    }
    
    public function changePassword(int $userId, string $password): void
    {
        $this->authRepository->changePassword($userId, $password);
    }
}