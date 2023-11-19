<?php
namespace SchedulingTerms\App\Repositories\Cached;

use RedisException;
use SchedulingTerms\App\Contracts\Repositories\JobRepositoryContract;
use SchedulingTerms\App\Dto\Jobs\CreateUpdateJobDto;
use SchedulingTerms\App\Helpers\Cache;
use SchedulingTerms\App\Models\Company;
use SchedulingTerms\App\Models\Job;

readonly class JobRepository implements JobRepositoryContract
{
    public function __construct(
        private JobRepositoryContract $repository,
        private Cache                 $cache)
    {
    }

    /**
     * @param CreateUpdateJobDto $jobDto
     * @return Job
     * @throws RedisException
     */
    public function create(CreateUpdateJobDto $jobDto): Job
    {
        $job = $this->repository->create($jobDto);
        $this->cache->set((string)$job->id, json_encode($job));

        return $job;
    }

    /**
     * @param int $id
     * @param CreateUpdateJobDto $jobDto
     * @return Job
     * @throws RedisException
     */
    public function update(int $id, CreateUpdateJobDto $jobDto): Job
    {
        $job = $this->repository->update($id, $jobDto);
        $this->cache->set((string)$job->id, json_encode($job));

        return $job;
    }
    
    /**
     * @throws RedisException
     */
    public function get(int $id): Job
    {
        $data = $this->cache->get((string)$id);
    
        if(!$data) {
            $data = $this->repository->get($id);
            $this->cache->set((string)$id, json_encode($data));
        
            return $data;
        }
    
        return new Job(
            $data->id,
            $data->name,
            $data->during,
            new Company(
                $data->company->id,
                $data->company->name,
                $data->company->createdAt,
                $data->company->updatedAt
            ),
            $data->createdAt,
            $data->updatedAt,
        );
    }
    
    public function paginate(int $perPage = self::PER_PAGE): array
    {
        return $this->repository->paginate($perPage);
    }
    
    /**
     * @throws RedisException
     */
    public function delete(int $id): void
    {
        $this->repository->delete($id);
        $this->redis->delete((string)$id);
    }
}