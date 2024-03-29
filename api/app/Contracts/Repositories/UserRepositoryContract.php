<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Contracts\Repositories;

use SchedulingTerms\App\Dto\Users\CreateUpdateUserDto;
use SchedulingTerms\App\Models\User;

interface UserRepositoryContract extends RepositoryContract
{
    public function create(CreateUpdateUserDto $userDto): User;
    public function update(int $id, CreateUpdateUserDto $userDto): User;
    public function getByEmail(string $email): User;
    public function paginateByCompanyId(int $cursor, int $companyId, int $perPage = self::PER_PAGE): array;
}