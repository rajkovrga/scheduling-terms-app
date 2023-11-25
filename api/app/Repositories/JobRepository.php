<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Repositories;

use Cake\Database\Connection;
use Cake\Database\Exception\DatabaseException;
use SchedulingTerms\App\Contracts\Repositories\JobRepositoryContract;
use SchedulingTerms\App\Dto\Jobs\CreateUpdateJobDto;
use SchedulingTerms\App\Exceptions\ModelNotFoundException;
use SchedulingTerms\App\Models\Company;
use SchedulingTerms\App\Models\Job;

readonly class JobRepository implements JobRepositoryContract
{
    public function __construct(
        private Connection $connection
    )
    {
    }
    
    /**
     * @throws ModelNotFoundException
     */
    public function get(int $id): Job
    {
        $data = $this->connection
            ->selectQuery([
                'jobs.id as id',
                'companies.id as companyId',
                'jobs.name as name',
                'jobs.during as during',
                'jobs.created_at as created_at',
                'jobs.updated_at as updated_at',
                'companies.name as companyName',
                'companies.created_at as companyCreatedAt',
                'companies.updated_at as companyUpdatedAt',
            ],
                'jobs')
            ->innerJoin('companies', ['jobs.company_id = companies.id'])
            ->where(['jobs.id' => $id])
            ->execute()
            ->fetch('assoc');
        
        if (!$data) {
            throw new ModelNotFoundException("Model not found");
        }
    
        return static::from($data);
    }
    
    public function paginate(int $cursor, int $perPage = self::PER_PAGE): array
    {
        $results = $this->connection
            ->selectQuery([
                'jobs.id as id',
                'companies.id as companyId',
                'jobs.name as name',
                'jobs.during as during',
                'jobs.created_at as created_at',
                'jobs.updated_at as updated_at',
                'companies.name as companyName',
                'companies.created_at as companyCreatedAt',
                'companies.updated_at as companyUpdatedAt',
            ], ['jobs'])
            ->innerJoin('companies', ['jobs.company_id = companies.id'])
            ->where(['id >' => $cursor])
            ->limit($perPage)
            ->execute()
            ->fetchAll('assoc');
    
        $data = [];
        foreach ($results as $result) {
            $data[] = static::from($result);
        }
    
        return $data;
    }
    
    /**
     * @throws ModelNotFoundException
     */
    public function delete(int $id): void
    {
        if ($this->connection->selectQuery('*', 'jobs')->where(['id' => $id])->rowCountAndClose() <= 0) {
            throw new ModelNotFoundException();
        }
        
        $this->connection->delete('jobs', ['id' => $id]);
    }
    
    /**
     * @param CreateUpdateJobDto $jobDto
     * @return Job
     * @throws ModelNotFoundException
     */
    public function create(CreateUpdateJobDto $jobDto): Job
    {
        $result = $this->connection->insertQuery('jobs', [
            'name' => $jobDto->name,
            'during' => $jobDto->during,
            'company_id' => $jobDto->companyId
        ]);
        
        if (!$result = $result->execute()) {
            throw new DatabaseException();
        }
        
        return $this->get($result->lastInsertId());
    }
    
    /**
     * @param int $id
     * @param CreateUpdateJobDto $jobDto
     * @return Job
     * @throws ModelNotFoundException
     */
    public function update(int $id, CreateUpdateJobDto $jobDto): Job
    {
        $result = $this->connection->updateQuery('jobs')
            ->set([
                'name' => $jobDto->name,
                'during' => $jobDto->during,
                'company_id' => $jobDto->companyId
            ])
            ->where(['id' => $id]);
        
        if (!$result->execute()) {
            throw new DatabaseException();
        }
    
        return $this->get($id);
    }
    public function paginateByCompanyId(int $cursor, int $companyId, int $perPage = self::PER_PAGE): array
    {
        $results = $this->connection
            ->selectQuery([
                'jobs.id as id',
                'companies.id as companyId',
                'jobs.name as name',
                'jobs.during as during',
                'jobs.created_at as created_at',
                'jobs.updated_at as updated_at',
                'companies.name as companyName',
                'companies.created_at as companyCreatedAt',
                'companies.updated_at as companyUpdatedAt',
            ], ['jobs'])
            ->innerJoin('companies', ['jobs.company_id = companies.id'])
            ->where(['id >' => $cursor])
            ->andWhere(['company_id' => $companyId])
            ->limit($perPage)
            ->execute()
            ->fetchAll('assoc');
    
        $data = [];
        foreach ($results as $result) {
            $data[] = static::from($result);
        }
    
        return $data;
    }
    
    private function from(array $data): Job {
        return new Job(
            $data['id'],
            $data['name'],
            $data['during'],
            new Company(
                $data['companyId'],
                $data['companyName'],
                $data['companyCreatedAt'],
                $data['companyUpdatedAt']
            ),
            $data['created_at'],
            $data['updated_at'],
        );
    }
    
}