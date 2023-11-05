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
        private readonly RepositoryContract $repositoryContract,
        private readonly Redis $redis
    )
    {
    
    }
    
    /**
     * @throws RedisException
     */
    public function get(int $id)
    {
        $data = $this->redis->get($id);
        
        if(!$data) {
            $data = $this->repositoryContract->get($id);
            $this->redis->set($id, $data);
        }
        
        return $data;
    }
    protected function getCacheKey(...$args): string
    {
        return '';
    }
    
    
    public function paginate(int $perPage = self::PER_PAGE): PaginateDto
    {
        return $this->repositoryContract->paginate($perPage);
    }
    
    /**
     * @throws RedisException
     */
    public function delete(int $id): void
    {
        $this->redis->del($id);
        $this->repositoryContract->delete($id);
    }
}