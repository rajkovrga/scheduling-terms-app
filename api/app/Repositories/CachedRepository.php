<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories;

use Redis;
use RedisException;
use SchedulingTerms\App\Contracts\Repositories\RepositoryContract;
use SchedulingTerms\App\Helpers\Cache;

class CachedRepository implements RepositoryContract
{
    public function __construct(
        private readonly RepositoryContract $repository,
        private readonly Cache $redis
    )
    {
    
    }
    
    /**
     * @throws RedisException
     */
    public function get(int $id)
    {
        $data = $this->redis->get(' .' . $id);
        
        if(!$data) {
            $data = $this->repository->get($id);
        }
        
        return $data;
    }
    
    public function paginate(int $perPage = self::PER_PAGE): array
    {
        return $this->repository->paginate($perPage);
    }
    
    /**
     * @throws RedisException
     */
    public function delete(int $id): void
    {
        $this->repository->delete($id);
        $this->redis->delete((string)$id);
    }
}