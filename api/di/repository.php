<?php
declare(strict_types=1);

use SchedulingTerms\App\Contracts\Repositories\AuthRepositoryContract;
use SchedulingTerms\App\Repositories\AuthRepository;
use SchedulingTerms\App\Repositories\Cached\CompanyRepository as CacheCompanyRepository;
use SchedulingTerms\App\Repositories\Cached\JobRepository as CacheJobRepository;
use SchedulingTerms\App\Repositories\Cached\TermRepository as CacheTermRepository;
use SchedulingTerms\App\Repositories\Cached\UserRepository as CacheUserRepository;
use SchedulingTerms\App\Repositories\Cached\TokenRepository as CacheTokenRepository;
use SchedulingTerms\App\Repositories\Cached\AuthRepository as CacheAuthRepository;
use SchedulingTerms\App\Repositories\CompanyRepository;
use SchedulingTerms\App\Repositories\JobRepository;
use SchedulingTerms\App\Repositories\TermRepository;
use SchedulingTerms\App\Repositories\TokenRepository;
use SchedulingTerms\App\Repositories\UserRepository;
use Cake\Database\Connection;
use Psr\Container\ContainerInterface as Container;
use SchedulingTerms\App\Contracts\Repositories\CompanyRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\JobRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\TermsRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\TokenRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\UserRepositoryContract;
use SchedulingTerms\App\Helpers\Cache;

return [
    CompanyRepositoryContract::class => static function (Container $container) {
        return new CacheCompanyRepository(
            new CompanyRepository(
                $container->get(Connection::class)),
            new Cache(
                $container->get(Redis::class),
                $container->get('company')['duration'],
                $container->get('company')['prefix']
            )
        );
    },
    UserRepositoryContract::class => static function (Container $container) {
        return new CacheUserRepository(
            new UserRepository(
                $container->get(Connection::class)),
            new Cache(
                $container->get(Redis::class),
                $container->get('user')['duration'],
                $container->get('user')['prefix']
            )
        );
    },
    TermsRepositoryContract::class => static function (Container $container) {
        return new CacheTermRepository(
            new TermRepository(
                $container->get(Connection::class)),
            new Cache(
                $container->get(Redis::class),
                $container->get('term')['duration'],
                $container->get('term')['prefix']
            )
        );
    },
    TokenRepositoryContract::class => static function (Container $container) {
        return new CacheTokenRepository(
            new TokenRepository(
                $container->get(Connection::class)),
            new Cache(
                $container->get(Redis::class),
                $container->get('token')['duration'],
                $container->get('token')['prefix']
            )
        );
    },
    JobRepositoryContract::class => static function (Container $container) {
        return new CacheJobRepository(
            new JobRepository(
                $container->get(Connection::class)),
            new Cache(
                $container->get(Redis::class),
                $container->get('job')['duration'],
                $container->get('job')['prefix']
            )
        );
    },
    AuthRepositoryContract::class => static function (Container $container) {
        return new CacheAuthRepository(
            new AuthRepository(
                $container->get(Connection::class)),
            new Cache(
                $container->get(Redis::class),
                $container->get('permission')['duration'],
                $container->get('permission')['prefix']
            )
        );
    },
    ];