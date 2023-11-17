<?php
namespace SchedulingTerms\App\Repositories\Cached;

use RedisException;
use SchedulingTerms\App\Contracts\Repositories\JobRepositoryContract;
use SchedulingTerms\App\Dto\Jobs\CreateUpdateJobDto;
use SchedulingTerms\App\Helpers\Cache;
use SchedulingTerms\App\Models\Job;
use SchedulingTerms\App\Repositories\CachedRepository;

class JobRepository extends CachedRepository implements JobRepositoryContract
{
    public function __construct(
        private readonly JobRepositoryContract $repository,
        private readonly Cache                  $cache)
    {
        parent::__construct($repository, $cache);
    }

    /**
     * @param CreateUpdateJobDto $jobDto
     * @return Job
     * @throws RedisException
     */
    public function create(CreateUpdateJobDto $jobDto): Job
    {
        $job = $this->repository->create($jobDto);
        $this->cache->set((string)$job->id, $job);

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
        $this->cache->set((string)$job->id, $job);

        return $job;
    }
}