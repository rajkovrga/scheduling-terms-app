<?php
declare(strict_types=1);

use Cake\Database\Connection;
use Carbon\CarbonInterval;
use Psr\Container\ContainerInterface as Container;
use SchedulingTerms\App\Contracts\Repositories\CompanyRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\JobRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\TermsRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\TokenRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\UserRepositoryContract;
use SchedulingTerms\App\Controllers\AuthController;
use SchedulingTerms\App\Controllers\CompanyController;
use SchedulingTerms\App\Controllers\JobController;
use SchedulingTerms\App\Controllers\TermController;
use SchedulingTerms\App\Controllers\UserController;
use SchedulingTerms\App\Helpers\Cache;
use Cake\Datasource\ConnectionManager;
use SchedulingTerms\App\Helpers\Hasher;
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
    Hasher::class => static function (Container $container) {
        return new Hasher();
    },
    JobController::class => static function (Container $container) {
        return new JobController(
            $container->get(JobRepositoryContract::class)
        );
    },
    CompanyController::class => static function (Container $container) {
        return new CompanyController(
            $container->get(CompanyRepositoryContract::class)
        );
    },
    TermController::class => static function (Container $container) {
        return new TermController(
            $container->get(TermsRepositoryContract::class)
        );
    },
    UserController::class => static function (Container $container) {
        return new UserController(
            $container->get(UserRepositoryContract::class),
            $container->get(Hasher::class)
        );
    },
    AuthController::class => static function (Container $container) {
        return new AuthController(
            $container->get(TokenRepositoryContract::class),
            $container->get(UserRepositoryContract::class),
            $container->get(Hasher::class)
        );
    },
];