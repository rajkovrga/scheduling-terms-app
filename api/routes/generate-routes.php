<?php
require_once __DIR__ . '/../vendor/autoload.php';

use DI\Container;
use SchedulingTerms\App\Core\Routing\RoutesGenerator;
use Slim\Factory\AppFactory;

$content = "
<?php
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
/** @var App $" . "app */

";

$registerRoutes = new RoutesGenerator(AppFactory::create(), new Container());
$content .= $registerRoutes->generateRoutes();

$file = fopen(__DIR__ . "/routes.php", "w") or die("Unable to open file!");
fwrite($file, $content);
fclose($file);
