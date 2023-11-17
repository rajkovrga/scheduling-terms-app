<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Contracts\Repositories;

use SchedulingTerms\App\Dto\Jobs\CreateUpdateJobDto;
use SchedulingTerms\App\Models\Job;

interface JobRepositoryContract extends RepositoryContract
{
    public function create(CreateUpdateJobDto $jobDto): Job;
    public function update(int $id, CreateUpdateJobDto $jobDto): Job;
    
}