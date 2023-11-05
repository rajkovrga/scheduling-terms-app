<?php
declare(strict_types=1);

use Cake\Database\Connection;
use Cake\Datasource\ConnectionInterface;
use Psr\Container\ContainerInterface as Container;
use SchedulingTerms\App\Contracts\Repositories\CompanyRepositoryContract;
use SchedulingTerms\App\Contracts\Repositories\JobRepositoryContract;
use SchedulingTerms\App\Controllers\CompanyController;
use SchedulingTerms\App\Controllers\JobController;
use SchedulingTerms\App\Repositories\CompanyRepository;
use SchedulingTerms\App\Repositories\JobRepository;
use Cake\Datasource\ConnectionManager;
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
//    JobRepositoryContract::class => static function (Container $container) {
//        return new JobRepository($container->get(Connection::class));
//    },
//    JobController::class => static function (Container $container) {
//        return new JobController($container->get(JobRepositoryContract::class));
//    },
    CompanyRepositoryContract::class => static function (Container $container) {
        return new CompanyRepository($container->get(Connection::class));
    },
    CompanyController::class => static function (Container $container) {
        return new CompanyController($container->get(CompanyRepositoryContract::class));
    },
];