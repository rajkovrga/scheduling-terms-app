<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Contracts\Repositories;

use SchedulingTerms\App\Dto\Pagination\PaginateDto;

/**
 * @template T
 * @template TCreate
 */
interface RepositoryContract
{
    public const PER_PAGE = 10;

    public function get(int $id);

    /**
     * @param int $perPage
     * @return PaginateDto<T>
     */
    public function paginate(int $perPage = self::PER_PAGE): PaginateDto;
    public function delete(int $id): void;
}