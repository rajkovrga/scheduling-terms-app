<?php

namespace SchedulingTerms\App\Repositories;

use SchedulingTerms\App\Contracts\Repositories\RepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\TermsRepositoryContract;
use SchedulingTerms\App\Core\Data\DatabaseConnection;
use SchedulingTerms\App\Dto\Jobs\JobDto;
use SchedulingTerms\App\Dto\Pagination\PaginateDto;

class JobRepository implements TermsRepositoryContract
{

    public function __construct(
        private DatabaseConnection $db
    )
    {
    }

    public function create($entity)
    {
        // TODO: Implement create() method.
    }

    public function get(int $id): ?JobDto
    {
        $result = $this->db->executeQuery('select * from jobs where id = ?', [$id])->fetch();

        if(!$result) {
            return null;
        }

        return JobDto::from($result);
    }

    public function paginate(int $perPage = self::PER_PAGE): PaginateDto
    {
        // TODO: Implement paginate() method.
    }

    public function delete(int $id): void
    {
        // TODO: Implement delete() method.
    }

    public function update(int $id, $entity)
    {
        // TODO: Implement update() method.
    }

    public function calculateTerms()
    {
        // TODO: Implement calculateTerms() method.
    }
}