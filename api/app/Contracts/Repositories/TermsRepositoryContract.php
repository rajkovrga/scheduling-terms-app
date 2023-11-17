<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Contracts\Repositories;

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
    public function calculateTerms(): array;
}