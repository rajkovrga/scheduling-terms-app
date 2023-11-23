<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Repositories;

use Cake\Database\Connection;
use Cake\Database\Exception\DatabaseException;
use SchedulingTerms\App\Contracts\Repositories\TokenRepositoryContract;
use SchedulingTerms\App\Dto\Tokens\CreateTokenDto;
use SchedulingTerms\App\Exceptions\ModelNotFoundException;
use SchedulingTerms\App\Exceptions\TokenAuthException;
use SchedulingTerms\App\Models\Token;
use SchedulingTerms\App\Models\User;

class TokenRepository implements TokenRepositoryContract
{
    public function __construct(
        private readonly Connection $connection
    )
    {
    }
    
    public function paginate(int $perPage = self::PER_PAGE): array
    {

    }

    /**
     * @throws ModelNotFoundException
     */
    public function delete(string $token): void
    {
        if($this->connection->selectQuery('*', 'tokens')->where(['token' => $token])->rowCountAndClose() <= 0) {
            throw new ModelNotFoundException();
        }

        $this->connection->delete('terms', ['token' => $token]);
    }

    /**
     * @param string $token
     * @return Token
     * @throws TokenAuthException
     */
    public function get(string $token): Token
    {
        $data = $this->connection
            ->selectQuery([
                'tokens.id as id',
                'users.id as userId',
                'users.email as email',
                'users.password as password',
                'users.role_id as roleId',
                'users.created_at as userCreatedAt',
                'users.updated_at as userUpdatedAt',
                'tokens.token as token'
            ],
                'tokens')
            ->innerJoin('users', ['users.id = tokens.user_id'])
            ->where(['tokens.token' => $token])
            ->execute()
            ->fetch('assoc');

        if (!$data) {
            throw new TokenAuthException("Model not found");
        }

        return new Token(
            $data['id'],
            new User(
                $data['userId'],
                $data['email'],
                $data['password'],
                null,
                $data['roleId'],
                $data['userCreatedAt'],
                $data['userUpdatedAt']),
            $data['token'],
        );
    }

    public function create(CreateTokenDto $tokenDto): Token
    {

        $data = $this->connection->insertQuery('tokens', [
            'token' => $tokenDto->token,
            'user_id' => $tokenDto->userId
        ])
            ->execute()
            ->fetch('assoc');

        if (!$data) {
            throw new DatabaseException();
        }

        return $this->get($data['token']);
    }

    public function getByUserId(int $userId): Token
    {
        $data = $this->connection
            ->selectQuery([
                'tokens.id as id',
                'users.id as userId',
                'users.email as email',
                'users.password as password',
                'users.role_id as roleId',
                'users.created_at as userCreatedAt',
                'users.updated_at as userUpdatedAt',
                'tokens.token as token'
            ],
                'tokens')
            ->innerJoin('users', ['users.id = tokens.user_id'])
            ->where(['tokens.user_id' => $userId])
            ->execute()
            ->fetch('assoc');

        if (!$data) {
            throw new TokenAuthException("Model not found");
        }

        return new Token(
            $data['id'],
            new User(
                $data['userId'],
                $data['email'],
                $data['password'],
                null,
                $data['roleId'],
                $data['userCreatedAt'],
                $data['userUpdatedAt']),
            $data['token'],
        );
    }
}