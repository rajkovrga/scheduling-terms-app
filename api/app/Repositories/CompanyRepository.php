<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories;

use Cake\Database\Connection;
use SchedulingTerms\App\Contracts\Repositories\CompanyRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\RepositoryContract;
use SchedulingTerms\App\Dto\Companies\CompanyDto;
use SchedulingTerms\App\Dto\Companies\CreateUpdateCompanyDto;
use SchedulingTerms\App\Dto\Companies\UpdateCompanyDto;
use SchedulingTerms\App\Dto\Pagination\PaginateDto;
use SchedulingTerms\App\Exceptions\ModelNotFoundException;

class CompanyRepository implements CompanyRepositoryContract
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
        $data = $this->connection->execute("select * from companies where id = ?", [$id])->fetch('assoc');
        if(!$data) {
            throw new ModelNotFoundException("Model not found");
        }
    
        return CompanyDto::from($data);
    }
    
    public function paginate(int $perPage = self::PER_PAGE): PaginateDto
    {
        // TODO: Implement paginate() method.
    }
    
    public function delete(int $id): void
    {
        $this->connection->delete('companies', ['id' => $id]);
    }
    
    public function create(CreateUpdateCompanyDto $companyDto): CompanyDto
    {
       $company = $this->connection->insert(
            'companies',
            ['name' => $companyDto->name]
        );
       
       return CompanyDto::from($company);
    }
    
    public function update(int $id, UpdateCompanyDto $companyDto): CompanyDto
    {
        $data = $this->connection->update('companies', ['name' => $companyDto->name] ,['id' => $companyDto->id])->fetch();
        
        return CompanyDto::from($data);
    }
}