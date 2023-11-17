<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories\Cached;

use SchedulingTerms\App\Contracts\Repositories\TermsRepositoryContract;
use SchedulingTerms\App\Dto\Terms\CreateUpdateTermDto;
use SchedulingTerms\App\Helpers\Cache;
use SchedulingTerms\App\Models\Term;
use SchedulingTerms\App\Repositories\CachedRepository;

class TermRepository extends CachedRepository implements TermsRepositoryContract
{
    public function __construct(
        private readonly TermsRepositoryContract $repository,
        private readonly Cache                  $cache)
    {
        parent::__construct($repository, $cache);
    }
    public function calculateTerms(): array
    {
        // TODO: Implement calculateTerms() method.
    }

    /**
     * @param CreateUpdateTermDto $termDto
     * @return Term
     * @throws \RedisException
     */
    public function create(CreateUpdateTermDto $termDto): Term
    {
        $term = $this->repository->create($termDto);
        $this->cache->set((string)$term->id, $term);

        return $term;
    }

    /**
     * @param int $id
     * @param CreateUpdateTermDto $termDto
     * @return Term
     * @throws \RedisException
     */
    public function update(int $id, CreateUpdateTermDto $termDto): Term
    {
        $term = $this->repository->update($id, $termDto);
        $this->cache->set((string)$term->id, $term);

        return $term;
    }
}