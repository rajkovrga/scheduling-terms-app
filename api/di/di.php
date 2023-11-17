<?php
declare(strict_types=1);

use Cake\Database\Connection;
use Psr\Container\ContainerInterface as Container;
use SchedulingTerms\App\Contracts\Repositories\CompanyRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\JobRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\TermsRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\TokenRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\UserRepositoryContract;
use SchedulingTerms\App\Controllers\JobController;
use SchedulingTerms\App\Helpers\Cache;
use Cake\Datasource\ConnectionManager;
use SchedulingTerms\App\Repositories\Cached\CompanyRepository as CacheCompanyRepository;
use SchedulingTerms\App\Repositories\Cached\TokenRepository as TokenCacheRepository;
use SchedulingTerms\App\Repositories\Cached\UserRepository as CacheUserRepository;
use SchedulingTerms\App\Repositories\Cached\TermRepository as CacheTermRepository;
use SchedulingTerms\App\Repositories\Cached\JobRepository as CacheJobRepository;
use SchedulingTerms\App\Repositories\CompanyRepository;
use SchedulingTerms\App\Repositories\JobRepository;
use SchedulingTerms\App\Repositories\TermRepository;
use SchedulingTerms\App\Repositories\TokenRepository;
use SchedulingTerms\App\Repositories\UserRepository;
use SchedulingTerms\App\Utils\Config;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;

return [
    Connection::class => static function (Container $container) {
        ConnectionManager::setConfig('default', $container->get(Config::class)->get('database.pgsql'));
        return ConnectionManager::get('default');
    },

    Redis::class => static function (Container $container) {
        $config = $container->get(Config::class)->get('database.redis');
        $redis = new Redis(
            $config['options']
        );

        if (!$redis->pconnect($config['host'], $config['port'], $config['connectTimeout'])) {
            throw new RedisException('Redis is not connect');
        }
        return $redis;
    },
    Mailer::class => static function (Container $container) {
        $config = $container->get(Config::class)->get('mail.smtp');
        $transport = Transport::fromDsn("gmail+smtp://{$config['username']}:{$config['password']}@default");
        return new Mailer($transport);
    },
    CompanyRepositoryContract::class => static function (Container $container) {
        new CacheCompanyRepository(
            new CompanyRepository(
                $container->get(Connection::class)),
            new Cache(
                $container->get(Redis::class),
                $container->get('company.cache_duration'),
                $container->get('company.cache_prefix')
            )
        );
    },
    UserRepositoryContract::class => static function (Container $container) {
        new CacheUserRepository(
            new UserRepository(
                $container->get(Connection::class)),
            new Cache(
                $container->get(Redis::class),
                $container->get('user.cache_duration'),
                $container->get('user.cache_prefix')
            )
        );
    },
    TermsRepositoryContract::class => static function (Container $container) {
        new CacheTermRepository(
            new TermRepository(
                $container->get(Connection::class)),
            new Cache(
                $container->get(Redis::class),
                $container->get('term.cache_duration'),
                $container->get('term.cache_prefix')
            )
        );
    },
    TokenRepositoryContract::class => static function (Container $container) {
        new TokenCacheRepository(
            new TokenRepository(
                $container->get(Connection::class)),
            new Cache(
                $container->get(Redis::class),
                $container->get('token.cache_duration'),
                $container->get('token.cache_prefix')
            )
        );
    },
    JobRepositoryContract::class => static function (Container $container) {
        new CacheJobRepository(
            new JobRepository(
                $container->get(Connection::class)),
            new Cache(
                $container->get(Redis::class),
                $container->get('job.cache_duration'),
                $container->get('job.cache_prefix')
            )
        );
    },
    JobController::class => static function (Container $container) {
//:TODO 
    }
];