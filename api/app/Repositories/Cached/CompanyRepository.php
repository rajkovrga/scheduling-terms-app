<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories\Cached;

use RedisException;
use SchedulingTerms\App\Contracts\Repositories\CompanyRepositoryContract;
use SchedulingTerms\App\Dto\Companies\CreateUpdateCompanyDto;
use SchedulingTerms\App\Helpers\Cache;
use SchedulingTerms\App\Models\Company;
use SchedulingTerms\App\Repositories\CachedRepository;

class CompanyRepository extends CachedRepository implements CompanyRepositoryContract
{
    public function __construct(
        private readonly CompanyRepositoryContract $repository,
        private readonly Cache                  $cache)
    {
        parent::__construct($repository, $cache);
    }

    /**
     * @param CreateUpdateCompanyDto $companyDto
     * @return Company
     * @throws RedisException
     */
    public function create(CreateUpdateCompanyDto $companyDto): Company
    {
        $company = $this->repository->create($companyDto);
        $this->cache->set((string)$company->id, $company);

        return $company;
    }

    /**
     * @param int $id
     * @param CreateUpdateCompanyDto $companyDto
     * @return Company
     * @throws RedisException
     */
    public function update(int $id, CreateUpdateCompanyDto $companyDto): Company
    {
        $company = $this->repository->update($id, $companyDto);
        $this->cache->set((string)$company->id, $company);

        return $company;
    }
}