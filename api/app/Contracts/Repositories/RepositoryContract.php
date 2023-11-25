<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Contracts\Repositories;


/**
 * @template T
 * @template TCreate
 */
interface RepositoryContract
{
    public const PER_PAGE = 10;

    public function get(int $id);
    
    /**
     * @param int $cursor
     * @param int $perPage
     * @return array<T>
     */
    public function paginate(int $cursor, int $perPage = self::PER_PAGE): array;
    public function delete(int $id): void;
}