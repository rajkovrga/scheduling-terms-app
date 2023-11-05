<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories;

use Cake\Database\Connection;
use Cake\Datasource\ConnectionInterface;
use SchedulingTerms\App\Contracts\Repositories\RepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\UserRepositoryContract;
use SchedulingTerms\App\Dto\Pagination\PaginateDto;

class UserRepository implements UserRepositoryContract
{
    public function __construct(
        private readonly Connection $connection
    )
    {
    }
    public function get(int $id)
    {
    }
    
    public function paginate(int $perPage = self::PER_PAGE): PaginateDto
    {
        // TODO: Implement paginate() method.
    }
    
    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }
}