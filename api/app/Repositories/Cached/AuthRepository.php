<?php

namespace SchedulingTerms\App\Repositories\Cached;

use RedisException;
use SchedulingTerms\App\Contracts\Repositories\AuthRepositoryContract;
use SchedulingTerms\App\Helpers\Cache;

readonly class AuthRepository implements AuthRepositoryContract
{
    public function __construct(
        private AuthRepositoryContract $authRepository,
        private Cache                  $roleCache
    )
    {
    }

    /**
     * @throws RedisException
     */
    public function getPermissions($roleId): array
    {
        $permissions = $this->roleCache->get($roleId);

        if(empty($permissions)) {
            $permissions = $this->authRepository->getPermissions($roleId);
            $this->roleCache->set($roleId, $permissions);
        }

        return $permissions;
    }
}