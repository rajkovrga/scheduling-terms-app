<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Repositories;

use SchedulingTerms\App\Contracts\Repositories\RepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\TokenRepositoryContract;
use SchedulingTerms\App\Dto\Pagination\PaginateDto;

class TokenRepository implements TokenRepositoryContract
{
    
    public function get(int $id)
    {
        // TODO: Implement get() method.
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