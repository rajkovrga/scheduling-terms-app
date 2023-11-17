<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories\Cached;

use SchedulingTerms\App\Contracts\Repositories\TermsRepositoryContract;
use SchedulingTerms\App\Dto\Terms\CreateUpdateTermDto;
use SchedulingTerms\App\Models\Term;
use SchedulingTerms\App\Repositories\CachedRepository;

class TermRepository extends CachedRepository implements TermsRepositoryContract
{
    
    public function calculateTerms()
    {
        // TODO: Implement calculateTerms() method.
    }
    
    /**
     * @param CreateUpdateTermDto $termDto
     * @return Term
     */
    public function create(CreateUpdateTermDto $termDto): Term
    {
        // TODO: Implement create() method.
    }
    
    /**
     * @param int $id
     * @param CreateUpdateTermDto $termDto
     * @return Term
     */
    public function update(int $id, CreateUpdateTermDto $termDto): Term
    {
        // TODO: Implement update() method.
    }
}