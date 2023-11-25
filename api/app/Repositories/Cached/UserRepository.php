<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories\Cached;

use RedisException;
use SchedulingTerms\App\Contracts\Repositories\UserRepositoryContract;
use SchedulingTerms\App\Dto\Users\CreateUpdateUserDto;
use SchedulingTerms\App\Helpers\Cache;
use SchedulingTerms\App\Models\Company;
use SchedulingTerms\App\Models\User;

readonly class UserRepository implements UserRepositoryContract
{
    public function __construct(
        private UserRepositoryContract $repository,
        private Cache                  $cache)
    {
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
        $this->cache->set((string)$user->id, json_encode($user));

        return $user;
    }
    
    /**
     * @throws RedisException
     */
    public function get(int $id): User
    {
    
        $data = $this->cache->get((string)$id);
    
        if(!$data) {
            $data = $this->repository->get($id);
            $this->cache->set((string)$id, json_encode($data));
        }
    
        return new User(
            $data->id,
            $data->email,
            $data->password,
            new Company(
                $data->company->id,
                $data->company->name,
                $data->company->createdAt,
                $data->company->updatedAt),
            $data->roleId,
            $data->createdAt,
            $data->updatedAt
        );
    }
    
     public function paginate(int $cursor, int $perPage = self::PER_PAGE): array

    {
        return $this->repository->paginate($cursor, $perPage);
    }
    
    /**
     * @throws RedisException
     */
    public function delete(int $id): void
    {
        $this->repository->delete($id);
        $this->cache->delete((string)$id);
    }

    public function getByEmail(string $email): User
    {
        return $this->repository->getByEmail($email);
    }
    
    public function paginateByCompanyId(int $cursor, int $companyId, int $perPage = self::PER_PAGE): array
    {
        return $this->repository->paginateByCompanyId($cursor, $companyId, $perPage);
    }
}