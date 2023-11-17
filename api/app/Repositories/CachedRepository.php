<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Repositories;

use Redis;
use RedisException;
use SchedulingTerms\App\Contracts\Repositories\RepositoryContract;
use SchedulingTerms\App\Dto\Pagination\PaginateDto;

class CachedRepository implements RepositoryContract
{
    public function __construct(
        private readonly RepositoryContract $repository,
        private readonly Redis $redis
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
    protected function getCacheKey(...$args): string
    {
        return '';
    }
    
    public function paginate(int $perPage = self::PER_PAGE): array
    {
        return $this->repositoryContract->paginate($perPage);
    }
    
    /**
     * @throws RedisException
     */
    public function delete(int $id): void
    {
        $this->repositoryContract->delete($id);
        $this->redis->del($id);
    }
}