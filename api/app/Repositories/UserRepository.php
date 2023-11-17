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
    
        return new User(
            $data['id'],
            $data['email'],
            $data['password'],
            new Company(
                $data['companyId'],
                $data['companyName'],
                $data['companyCreatedAt'],
                $data['companyUpdatedAt']),
            $data['role_id'],
            $data['created_at'],
            $data['updated_at']
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
        if($this->connection->selectQuery('*', 'users')->where(['id' => $id])->rowCountAndClose() <= 0) {
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
}