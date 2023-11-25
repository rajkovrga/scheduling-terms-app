<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories;

use Cake\Database\Connection;
use Cake\Database\Exception\DatabaseException;
use SchedulingTerms\App\Contracts\Repositories\UserRepositoryContract;
use SchedulingTerms\App\Dto\Users\CreateUpdateUserDto;
use SchedulingTerms\App\Exceptions\ModelNotFoundException;
use SchedulingTerms\App\Models\Company;
use SchedulingTerms\App\Models\Term;
use SchedulingTerms\App\Models\User;

readonly class UserRepository implements UserRepositoryContract
{
    public function __construct(
        private Connection $connection
    )
    {
    }
    
    /**
     * @throws ModelNotFoundException
     */
    public function get(int $id): User
    {
        $data = $this->connection
            ->selectQuery([
                'users.id as id',
                'users.email as email',
                'users.password as password',
                'users.created_at as created_at',
                'users.updated_at as updated_at',
                'companies.id as companyId',
                'companies.name as companyName',
                'companies.created_at as companyCreatedAt',
                'companies.updated_at as companyUpdatedAt',
                'users.role_id as role_id'
            ],
                'users')
            ->leftJoin('companies', ['users.company_id = companies.id'])
            ->where(['users.id' => $id])
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
                'users.id as id',
                'users.email as email',
                'users.password as password',
                'users.created_at as created_at',
                'users.updated_at as updated_at',
                'companies.id as companyId',
                'companies.name as companyName',
                'companies.created_at as companyCreatedAt',
                'companies.updated_at as companyUpdatedAt',
                'users.role_id as role_id'
            ],
                'users')
            ->leftJoin('companies', ['users.company_id = companies.id'])
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
        if ($this->connection->selectQuery('*', 'users')->where(['id' => $id])->rowCountAndClose() <= 0) {
            throw new ModelNotFoundException();
        }
        
        $this->connection->delete('users', ['id' => $id]);
    }
    
    /**
     * @param CreateUpdateUserDto $userDto
     * @return User
     * @throws ModelNotFoundException
     */
    public function create(CreateUpdateUserDto $userDto): User
    {
        $data = $this->connection->insertQuery('users', [
            'email' => $userDto->email,
            'role_id' => $userDto->roleId,
            'password' => $userDto->roleId,
            'company_id' => $userDto->companyId
        ]);
        
        if (!$data = $data->execute()) {
            throw new DatabaseException();
        }
        
        $id = $data->lastInsertId();
        
        return $this->get(intval($id));
    }
    
    /**
     * @param int $id
     * @param CreateUpdateUserDto $userDto
     * @return User
     * @throws ModelNotFoundException
     */
    public function update(int $id, CreateUpdateUserDto $userDto): User
    {
        $query = $this->connection->updateQuery('users')
            ->set([
                'email' => $userDto->email,
                'role_id' => $userDto->roleId,
                'company_id' => $userDto->companyId
            ])
            ->where(['id' => $id]);
        
        if (!$query->execute()) {
            throw new DatabaseException();
        }
        
        return $this->get($id);
    }
    
    /**
     * @throws ModelNotFoundException
     */
    public function getByEmail(string $email): User
    {
        $user = $this->connection->selectQuery(['*'], 'users')
            ->where(['email' => $email])
            ->execute()
            ->fetch('assoc');
        
        if (!$user) {
            throw new ModelNotFoundException("Model not found");
        }
    
        return static::from($user);
    }
    
    public function paginateByCompanyId(int $cursor, int $companyId, int $perPage = self::PER_PAGE): array
    {
        $results = $this->connection
            ->selectQuery([
                'users.id as id',
                'users.email as email',
                'users.password as password',
                'users.created_at as created_at',
                'users.updated_at as updated_at',
                'companies.id as companyId',
                'companies.name as companyName',
                'companies.created_at as companyCreatedAt',
                'companies.updated_at as companyUpdatedAt',
                'users.role_id as role_id'
            ],
                'users')
            ->leftJoin('companies', ['users.company_id = companies.id'])
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
    
    private function from(array $data): User
    {
        return new User(
            $data['id'],
            $data['email'],
            $data['password'],
            new Company(
                $data['companyId'] ?? null,
                $data['companyName'] ?? null,
                $data['companyCreatedAt'] ?? null,
                $data['companyUpdatedAt'] ?? null),
            $data['role_id'],
            $data['created_at'],
            $data['updated_at']
        );
    }
}