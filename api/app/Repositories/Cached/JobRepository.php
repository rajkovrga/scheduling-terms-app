<?php
namespace SchedulingTerms\App\Repositories\Cached;

use SchedulingTerms\App\Contracts\Repositories\JobRepositoryContract;
use SchedulingTerms\App\Dto\Jobs\CreateUpdateJobDto;
use SchedulingTerms\App\Models\Job;
use SchedulingTerms\App\Repositories\CachedRepository;

class JobRepository extends CachedRepository implements JobRepositoryContract
{
    
    /**
     * @param CreateUpdateJobDto $jobDto
     * @return Job
     */
    public function create(CreateUpdateJobDto $jobDto): Job
    {
        // TODO: Implement create() method.
    }
    
    /**
     * @param int $id
     * @param CreateUpdateJobDto $jobDto
     * @return Job
     */
    public function update(int $id, CreateUpdateJobDto $jobDto): Job
    {
        // TODO: Implement update() method.
    }
}