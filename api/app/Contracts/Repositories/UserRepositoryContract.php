<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Contracts\Repositories;

use SchedulingTerms\App\Dto\Users\CreateUpdateUserDto;
use SchedulingTerms\App\Models\User;

interface UserRepositoryContract extends RepositoryContract
{
    public function create(CreateUpdateUserDto $userDto): User;
    public function update(int $id, CreateUpdateUserDto $userDto): User;
    public function findByEmail(string $email): User;
}