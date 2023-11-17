<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Repositories;

use Cake\Database\Connection;
use Cake\Database\Exception\DatabaseException;
use SchedulingTerms\App\Contracts\Repositories\CompanyRepositoryContract;
use SchedulingTerms\App\Dto\Companies\CreateUpdateCompanyDto;
use SchedulingTerms\App\Exceptions\ModelNotFoundException;
use SchedulingTerms\App\Models\Company;

readonly class CompanyRepository implements CompanyRepositoryContract
{
    public function __construct(
        private Connection $connection
    )
    {
    }
    
    /**
     * @throws ModelNotFoundException
     */
    public function get(int $id): Company
    {
        $data = $this->connection
            ->selectQuery(
                '*',
                'companies')
            ->where(['id' => $id])
            ->execute()
            ->fetch('assoc');
        
        if (!$data) {
            throw new ModelNotFoundException("Model not found");
        }
        
        return new Company(
            $data['id'],
            $data['name'],
            $data['created_at'],
            $data['updated_at']
        );
    }
    
    public function paginate(int $perPage = self::PER_PAGE): array
    {
        // TODO: Implement paginate() method.
        return [];
    }
    
    /**
     * @throws ModelNotFoundException
     */
    public function delete(int $id): void
    {
        if($this->connection->selectQuery('*', 'companies')->where(['id' => $id])->rowCountAndClose() <= 0) {
            throw new ModelNotFoundException();
        }
        
        $this->connection->delete('companies', ['id' => $id]);
    }
    
    public function create(CreateUpdateCompanyDto $companyDto): Company
    {
        $data = $this->connection->insertQuery('companies', [
            'name' => $companyDto->name
        ])
        ->execute()
        ->fetch('assoc');

        if (!$data) {
            throw new DatabaseException();
        }
        
        return new Company(
            $data['id'],
            $data['name'],
            $data['created_at'],
            $data['updated_at']
        );
    }
    
    /**
     * @throws ModelNotFoundException
     */
    public function update(int $id, CreateUpdateCompanyDto $companyDto): Company
    {
        $existsQuery = $this->connection
            ->selectQuery(['id'], 'companies')
            ->where(['id' => $id])
            ->execute()
            ->fetch('assoc');
        
        if (!$existsQuery) {
            throw new ModelNotFoundException();
        }
        
        $updateQuery = $this->connection->updateQuery('companies')
            ->set(['name' => $companyDto->name])
            ->where(['id' => $id]);
        
        if (!$updateQuery->execute()) {
            throw new DatabaseException();
        }
        
        return $this->get($id);
    }
}