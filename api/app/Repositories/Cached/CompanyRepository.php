<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories\Cached;

use SchedulingTerms\App\Contracts\Repositories\CompanyRepositoryContract;
use SchedulingTerms\App\Dto\Companies\CreateUpdateCompanyDto;
use SchedulingTerms\App\Models\Company;
use SchedulingTerms\App\Repositories\CachedRepository;

class CompanyRepository extends CachedRepository implements CompanyRepositoryContract
{
    
    /**
     * @param CreateUpdateCompanyDto $companyDto
     * @return Company
     */
    public function create(CreateUpdateCompanyDto $companyDto): Company
    {
        // TODO: Implement create() method.
    }
    
    /**
     * @param int $id
     * @param CreateUpdateCompanyDto $companyDto
     * @return Company
     */
    public function update(int $id, CreateUpdateCompanyDto $companyDto): Company
    {
        // TODO: Implement update() method.
    }
}