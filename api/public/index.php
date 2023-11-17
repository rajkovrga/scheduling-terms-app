<?php
declare(strict_types=1);

use DI\Bridge\Slim\Bridge;
use SchedulingTerms\App\Core\Loaders\DILoader;
use SchedulingTerms\App\Kernel;
require_once __DIR__ . '/../vendor/autoload.php';

define('APP_BASE_PATH', dirname(__DIR__));
define('APP_CONTROLLERS', implode(DIRECTORY_SEPARATOR, [APP_BASE_PATH, 'app', 'Controllers']));
define('APP_ROUTES', implode(DIRECTORY_SEPARATOR, [APP_BASE_PATH, 'routes', 'routes.php']));
const APP_NAMESPACE = 'SchedulingTerms\\App';

$di = new DILoader(APP_BASE_PATH);

$app = Bridge::create($di->getContainer());
(new Kernel(
    $app,
    $di,
    APP_NAMESPACE,
    APP_BASE_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR,
    APP_CONTROLLERS,
    APP_ROUTES
))
    ->run();
