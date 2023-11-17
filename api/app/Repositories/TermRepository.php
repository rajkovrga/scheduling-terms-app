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

readonly class TermRepository implements TermsRepositoryContract
{
    public function __construct(
        private ConnectionInterface $connection
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
    
    public function paginate(int $perPage = self::PER_PAGE): array
    {
        // TODO: Implement paginate() method.
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
     * @param CreateUpdateTermDto $termDto
     * @return Term
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
     * @param int $id
     * @param CreateUpdateTermDto $termDto
     * @return Term
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
    
    /**
     * @return array
     */
    public function calculateTerms(): array
    {
        // TODO: Implement calculateTerms() method.
    }
}