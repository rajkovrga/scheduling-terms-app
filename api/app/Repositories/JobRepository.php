<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Repositories;

use Cake\Database\Connection;
use SchedulingTerms\App\Contracts\Repositories\JobRepositoryContract;
use SchedulingTerms\App\Dto\Companies\CreateUpdateCompanyDto;
use SchedulingTerms\App\Dto\Jobs\JobDto;
use SchedulingTerms\App\Dto\Jobs\UpdateJobDto;
use SchedulingTerms\App\Dto\Pagination\PaginateDto;
use SchedulingTerms\App\Exceptions\ModelNotFoundException;

class JobRepository implements JobRepositoryContract
{
    public function __construct(
        private readonly Connection $connection
    )
    {
    }
    
    /**
     * @throws ModelNotFoundException
     */
    public function get(int $id)
    {
        $data = $this->connection->execute("select * from jobs where id = ?", [$id])->fetch();
        
        if(!$data) {
            throw new ModelNotFoundException("Model not found");
        }
        
        return JobDto::from($data);
    }
    
    public function paginate(int $perPage = self::PER_PAGE): PaginateDto
    {
        // TODO: Implement paginate() method.
    }
    
    public function delete(int $id): void
    {
        $this->connection->delete('companies', ['id' => $id]);
    }
    
    /**
     * @param CreateUpdateCompanyDto $jobDto
     * @return JobDto
     */
    public function create(CreateUpdateCompanyDto $jobDto): JobDto
    {
        $job = $this->connection->insert(
            'jobs',
            [
                'name' => $jobDto->name,
                'during' => $jobDto->during
            ]
        )->fetch();
        
        return JobDto::from($job);
    }
    
    /**
     * @param UpdateJobDto $jobDto
     * @return JobDto
     */
    public function update(UpdateJobDto $jobDto): JobDto
    {
        $data = $this->connection->update(
            'jobs',
            ['name' => $jobDto->name],
            ['id' => $jobDto->id]
        )->fetch();
        
        return JobDto::from($data);
    }
}