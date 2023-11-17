<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories\Cached;

use SchedulingTerms\App\Contracts\Repositories\UserRepositoryContract;
use SchedulingTerms\App\Dto\Users\CreateUpdateUserDto;
use SchedulingTerms\App\Models\User;
use SchedulingTerms\App\Repositories\CachedRepository;

class UserRepository extends CachedRepository implements UserRepositoryContract
{
    
    /**
     * @param CreateUpdateUserDto $userDto
     * @return User
     */
    public function create(CreateUpdateUserDto $userDto): User
    {
        // TODO: Implement create() method.
    }
    
    /**
     * @param int $id
     * @param CreateUpdateUserDto $userDto
     * @return User
     */
    public function update(int $id, CreateUpdateUserDto $userDto): User
    {
        // TODO: Implement update() method.
    }
}