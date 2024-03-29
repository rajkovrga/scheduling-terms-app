<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories;

use Cake\Database\Exception\DatabaseException;
use Cake\Datasource\ConnectionInterface;
use Carbon\CarbonImmutable;
use SchedulingTerms\App\Contracts\Repositories\TermsRepositoryContract;
use SchedulingTerms\App\Dto\Terms\CreateUpdateTermDto;
use SchedulingTerms\App\Exceptions\ModelNotFoundException;
use SchedulingTerms\App\Models\Company;
use SchedulingTerms\App\Models\Job;
use SchedulingTerms\App\Models\Term;
use SchedulingTerms\App\Models\User;
use SchedulingTerms\App\Utils\Config;

readonly class TermRepository implements TermsRepositoryContract
{
    public function __construct(
        private ConnectionInterface $connection,
        private Config $config
    )
    {
    }
    
    /**
     * @throws ModelNotFoundException
     */
    public function get(int $id): Term
    {
        $data = $this->connection
            ->selectQuery([
                'terms.id as id',
                'jobs.id as jobId',
                'jobs.name as jobName',
                'jobs.during as during',
                'jobs.created_at as jobCreatedAt',
                'jobs.updated_at as jobUpdatedAt',
                'companies.id as companyId',
                'companies.name as companyName',
                'companies.created_at as companyCreatedAt',
                'companies.updated_at as companyUpdatedAt',
                'users.id as userId',
                'users.email as email',
                'users.password as password',
                'users.role_id as roleId',
                'users.created_at as userCreatedAt',
                'users.updated_at as userUpdatedAt',
                'terms.start_date as startDate',
                'terms.end_date as endDate'
            ],
                'terms')
            ->innerJoin('jobs', ['jobs.id = terms.job_id'])
            ->innerJoin('companies', ['companies.id = terms.company_id'])
            ->innerJoin('users', ['users.id = terms.user_id'])
            ->where(['terms.id' => $id])
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
                'terms.id as id',
                'jobs.id as jobId',
                'jobs.name as jobName',
                'jobs.during as during',
                'jobs.created_at as jobCreatedAt',
                'jobs.updated_at as jobUpdatedAt',
                'companies.id as companyId',
                'companies.name as companyName',
                'companies.created_at as companyCreatedAt',
                'companies.updated_at as companyUpdatedAt',
                'users.id as userId',
                'users.email as email',
                'users.password as password',
                'users.role_id as roleId',
                'users.created_at as userCreatedAt',
                'users.updated_at as userUpdatedAt',
                'terms.start_date as startDate',
                'terms.end_date as endDate'
            ],
                'terms')
            ->innerJoin('jobs', ['jobs.id = terms.job_id'])
            ->innerJoin('companies', ['companies.id = terms.company_id'])
            ->innerJoin('users', ['users.id = terms.user_id'])
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
        if($this->connection->selectQuery('*', 'terms')->where(['id' => $id])->rowCountAndClose() <= 0) {
            throw new ModelNotFoundException();
        }
    
        $this->connection->delete('terms', ['id' => $id]);
    }
    
    /**
     * @throws ModelNotFoundException
     */
    public function create(CreateUpdateTermDto $termDto): Term
    {
        $job = $this->connection
            ->selectQuery('*', 'jobs')
            ->where(['id' => $termDto->jobId])
            ->execute()
            ->fetch('assoc');
    
        $data = $this->connection->insertQuery('terms', [
            'job_id' => $termDto->jobId,
            'company_id' => $termDto->companyId,
            'start_date' => $termDto->startDate,
            'end_date' => CarbonImmutable::make($termDto->startDate)
                ->addMinutes($job['during']),
            'user_id' => $termDto->userId
        ]);
    
        if (!$data = $data->execute()) {
            throw new DatabaseException();
        }
        
        $id = $data->lastInsertId();
    
        return $this->get(intval($id));
    }
    
    /**
     * @throws ModelNotFoundException
     */
    public function update(int $id, CreateUpdateTermDto $termDto): Term
    {
        $job = $this->connection
            ->selectQuery('*', 'jobs')
            ->where(['id' => $termDto->jobId])
            ->execute()
            ->fetch('assoc');
        
        $query = $this->connection->updateQuery('terms')
            ->set([
                'job_id' => $termDto->jobId,
                'company_id' => $termDto->companyId,
                'start_date' => $termDto->startDate,
                'end_date' => CarbonImmutable::make($termDto->startDate)
                    ->addMinutes($job['during']),
                'user_id' => $termDto->userId
            ])
            ->where(['id' => $id]);
    
        if (!$query->execute()) {
            throw new DatabaseException();
        }
    
        return $this->get($id);
    }
    
    public function paginateByCompanyId(int $cursor, int $companyId, int $perPage = self::PER_PAGE): array
    {
        $results = $this->connection
            ->selectQuery([
                'terms.id as id',
                'jobs.id as jobId',
                'jobs.name as jobName',
                'jobs.during as during',
                'jobs.created_at as jobCreatedAt',
                'jobs.updated_at as jobUpdatedAt',
                'companies.id as companyId',
                'companies.name as companyName',
                'companies.created_at as companyCreatedAt',
                'companies.updated_at as companyUpdatedAt',
                'users.id as userId',
                'users.email as email',
                'users.password as password',
                'users.role_id as roleId',
                'users.created_at as userCreatedAt',
                'users.updated_at as userUpdatedAt',
                'terms.start_date as startDate',
                'terms.end_date as endDate'
            ],
                'terms')
            ->innerJoin('jobs', ['jobs.id = terms.job_id'])
            ->innerJoin('companies', ['companies.id = terms.company_id'])
            ->innerJoin('users', ['users.id = terms.user_id'])
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
    
    public function calculateTerms(int $companyId, int $userId, int $jobId, CarbonImmutable $date): array
    {
        $terms = $this->connection
            ->selectQuery([
                'start_date as startTerm',
                'end_date as endTerm'
            ], 'terms')
            ->where(['terms.user_id' => $userId])
            ->execute()
            ->fetchAll();
        
       
        $minTimeInterval = $this->config->get('minimalTimeInterval', 15);
        
        $startWt = 9 * 60; //TODO: get worktime from start to finish from db
        
        $startTime = 8; //TODO: get from db
        $intervalList = [];
        
        $countTerms = $startWt / $minTimeInterval;
        
        for ($i = 0; $i < $countTerms; $i++) {
            $intervalList[] = $i * $minTimeInterval;
        }
        
        // calculate interval
        
        
        
        $result = [];
    
        foreach ($intervalList as $item) {
            $result[] = $date->setHour($startTime)->setMinutes($item);
        }
        
        return $terms;
    }
    
    private function from(array $data): Term {
        return new Term(
            $data['id'],
            new Job(
                $data['jobId'],
                $data['jobName'],
                $data['during'],
                null,
                $data['jobCreatedAt'],
                $data['jobUpdatedAt']
            ),
            new Company(
                $data['companyId'],
                $data['companyName'],
                $data['companyCreatedAt'],
                $data['companyUpdatedAt']),
            new User(
                $data['userId'],
                $data['email'],
                $data['password'],
                null,
                $data['roleId'],
                $data['userCreatedAt'],
                $data['userUpdatedAt']),
            $data['startDate'],
            $data['endDate']
        );
    }
}