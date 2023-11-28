<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories\Cached;

use Carbon\CarbonImmutable;
use RedisException;
use SchedulingTerms\App\Contracts\Repositories\TermsRepositoryContract;
use SchedulingTerms\App\Dto\Terms\CreateUpdateTermDto;
use SchedulingTerms\App\Helpers\Cache;
use SchedulingTerms\App\Models\Company;
use SchedulingTerms\App\Models\Job;
use SchedulingTerms\App\Models\Term;
use SchedulingTerms\App\Models\User;

readonly class TermRepository implements TermsRepositoryContract
{
    public function __construct(
        private TermsRepositoryContract $repository,
        private Cache                   $cache)
    {
    }

    /**
     * @throws RedisException
     */
    public function create(CreateUpdateTermDto $termDto): Term
    {
        $term = $this->repository->create($termDto);
        $this->cache->set((string)$term->id, json_encode($term));

        return $term;
    }

    /**
     * @throws RedisException
     */
    public function update(int $id, CreateUpdateTermDto $termDto): Term
    {
        $term = $this->repository->update($id, $termDto);
        $this->cache->set((string)$term->id, json_encode($term));

        return $term;
    }
    
    /**
     * @throws RedisException
     */
    public function get(int $id): Term
    {
        $data = $this->cache->get((string)$id);
    
        if(!$data) {
            $data = $this->repository->get($id);
            $this->cache->set((string)$id, json_encode($data));
        
            return $data;
        }
    
        return new Term(
            $data->id,
            new Job(
                $data->job->id,
                $data->job->name,
                $data->job->during,
                null,
                $data->job->createdAt,
                $data->job->updatedAt
            ),
            new Company(
                $data->company->id,
                $data->company->name,
                $data->company->createdAt,
                $data->company->updatedAt),
            new User(
                $data->user->id,
                $data->user->email,
                $data->user->password,
                null,
                $data->user->roleId,
                $data->user->createdAt,
                $data->user->updatedAt),
            $data->startDate,
            $data->endDate
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
    
    public function paginateByCompanyId(int $cursor, int $companyId, int $perPage = self::PER_PAGE): array
    {
        return $this->repository->paginateByCompanyId($cursor, $companyId, $perPage);
    }
    
    public function calculateTerms(int $companyId, int $userId, int $jobId, CarbonImmutable $date): array
    {
        return $this->repository->calculateTerms($companyId, $userId, $jobId, $date);
    }
}