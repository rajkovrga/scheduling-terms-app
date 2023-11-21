<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories\Cached;

use RedisException;
use SchedulingTerms\App\Contracts\Repositories\TokenRepositoryContract;
use SchedulingTerms\App\Dto\Tokens\CreateTokenDto;
use SchedulingTerms\App\Helpers\Cache;
use SchedulingTerms\App\Models\Token;

readonly class TokenRepository implements TokenRepositoryContract
{
    public function __construct(
        private TokenRepositoryContract $repository,
        private Cache                   $cache
    )
    {
    }

    /**
     * @throws RedisException
     */
    public function create(CreateTokenDto $tokenDto): Token
    {
        $token = $this->repository->create($tokenDto);
        $this->cache->set($token->token, $token);

        return $token;
    }

    /**
     * @throws RedisException
     */
    public function get(string $token): Token
    {
        $data = $this->cache->get($token);

        if($data === null) {
            $data = $this->repository->get($token);
            $this->cache->set($data->token, json_encode($data));
        
            return $data;
        }

        return new Token(
            $data->id,
            $data->token,
            $data->userId
        );
    }

    public function paginate(int $perPage = self::PER_PAGE): array
    {
        return $this->repository->paginate($perPage);
    }

    /**
     * @throws RedisException
     */
    public function delete(string $token): void
    {
        $this->repository->delete($token);
        $this->cache->delete($token);
    }
}