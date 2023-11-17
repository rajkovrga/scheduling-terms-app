<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories\Cached;

use RedisException;
use SchedulingTerms\App\Contracts\Repositories\UserRepositoryContract;
use SchedulingTerms\App\Dto\Users\CreateUpdateUserDto;
use SchedulingTerms\App\Helpers\Cache;
use SchedulingTerms\App\Models\User;
use SchedulingTerms\App\Repositories\CachedRepository;

class UserRepository extends CachedRepository implements UserRepositoryContract
{
    public function __construct(
        private readonly UserRepositoryContract $repository,
        private readonly Cache                  $cache)
    {
        parent::__construct($repository, $cache);
    }

    /**
     * @param CreateUpdateUserDto $userDto
     * @return User
     * @throws RedisException
     */
    public function create(CreateUpdateUserDto $userDto): User
    {
        $user = $this->repository->create($userDto);
        $this->cache->set((string)$user->id, json_encode($user));

        return $user;
    }

    /**
     * @param int $id
     * @param CreateUpdateUserDto $userDto
     * @return User
     * @throws RedisException
     */
    public function update(int $id, CreateUpdateUserDto $userDto): User
    {
        $user = $this->repository->update($id, $userDto);
        $this->cache->set((string)$user->id, $user);

        return $user;
    }
}