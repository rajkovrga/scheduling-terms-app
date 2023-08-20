<?php
declare(strict_types=1);

use DI\Bridge\Slim\Bridge;
use SchedulingTerms\App\Core\Kernel;
use SchedulingTerms\App\Core\Loaders\DILoader;
use SchedulingTerms\App\Core\Loaders\EnvLoader;
use SchedulingTerms\App\Utils\Config;

require_once __DIR__ . '/../vendor/autoload.php';

define('APP_BASE_PATH', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'));

$config = new Config(APP_BASE_PATH);
$di = new DILoader($config, APP_BASE_PATH);

$app = Bridge::create($di->container);
    (new Kernel($app, $di->container))->routes()->run();
