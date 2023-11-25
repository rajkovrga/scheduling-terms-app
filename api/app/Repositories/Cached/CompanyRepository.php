<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories\Cached;

use RedisException;
use SchedulingTerms\App\Contracts\Repositories\CompanyRepositoryContract;
use SchedulingTerms\App\Dto\Companies\CreateUpdateCompanyDto;
use SchedulingTerms\App\Helpers\Cache;
use SchedulingTerms\App\Models\Company;

readonly class CompanyRepository implements CompanyRepositoryContract
{
    public function __construct(
        private CompanyRepositoryContract $repository,
        private Cache                     $cache)
    {
    }

    /**
     * @param CreateUpdateCompanyDto $companyDto
     * @return Company
     * @throws RedisException
     */
    public function create(CreateUpdateCompanyDto $companyDto): Company
    {
        $company = $this->repository->create($companyDto);
        $this->cache->set((string)$company->id, json_encode($company));

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
        $this->cache->set((string)$company->id, json_encode($company));

        return $company;
    }
    
    /**
     * @throws RedisException
     */
    public function get(int $id): Company
    {
        $data = $this->cache->get((string)$id);
        
        if(!$data) {
            $data = $this->repository->get($id);
            $this->cache->set((string)$id, json_encode($data));
            
            return $data;
        }
    
        return new Company(
            $data->id,
            $data->name,
            $data->createdAt,
            $data->updatedAt
        );
    }
    
    public function paginate(int $cursor, int $perPage = self::PER_PAGE): array
    {
        return $this->repository->paginate($cursor, $perPage);
    }
    
    /**
     * @throws RedisException
     */
    public function delete(int $id): void
    {
        $this->repository->delete($id);
        $this->cache->delete((string)$id);
    }
}