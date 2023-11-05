<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories;

use Cake\Datasource\ConnectionInterface;
use SchedulingTerms\App\Contracts\Repositories\RepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\TermsRepositoryContract;
use SchedulingTerms\App\Dto\Pagination\PaginateDto;

class TermRepository implements TermsRepositoryContract
{
    public function __construct(
        private readonly ConnectionInterface $connection
    )
    {
    }
    
    public function get(int $id)
    {
        return $this->connection->execute("");
    }
    
    public function paginate(int $perPage = self::PER_PAGE): PaginateDto
    {
        // TODO: Implement paginate() method.
    }
    
    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }
    
    public function calculateTerms()
    {
        // TODO: Implement calculateTerms() method.
    }
}