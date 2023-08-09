<?php

namespace SchedulingTerms\App\Repositories;

use SchedulingTerms\App\Contracts\Repositories\RepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\TermsRepositoryContract;
use SchedulingTerms\App\Dto\Pagination\PaginateDto;

class TermRepository implements TermsRepositoryContract
{

    public function create($entity)
    {
        // TODO: Implement create() method.
    }

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

    public function update(int $id, $entity)
    {
        // TODO: Implement update() method.
    }

    public function calculateTerms()
    {
        // TODO: Implement calculateTerms() method.
    }
}