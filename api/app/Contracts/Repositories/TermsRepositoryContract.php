<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Contracts\Repositories;

use Carbon\CarbonImmutable;
use SchedulingTerms\App\Dto\Terms\CreateUpdateTermDto;
use SchedulingTerms\App\Models\Term;

/**
 * @template T
 */
interface TermsRepositoryContract extends RepositoryContract
{
    public function create(CreateUpdateTermDto $termDto): Term;
    public function update(int $id, CreateUpdateTermDto $termDto): Term;
    /**
     * @return array<T>
     */
    public function calculateTerms(int $companyId, int $userId, int $jobId, CarbonImmutable $date): array;
    public function paginateByCompanyId(int $cursor, int $companyId, int $perPage = self::PER_PAGE): array;
}