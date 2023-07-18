<?php

use DI\Bridge\Slim\Bridge;
use SchedulingTerms\App\Core\Kernel;
use SchedulingTerms\App\Core\Loaders\DILoader;
use SchedulingTerms\App\Core\Loaders\EnvLoader;

require_once __DIR__ . '/../vendor/autoload.php';

(new EnvLoader())->load();
$container = (new DILoader())->load();

$app = Bridge::create($container);
    (new Kernel($app, $container))->routes()->run();
