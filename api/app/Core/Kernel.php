<?php

declare(strict_types=1);

namespace SchedulingTerms\App\Core;

use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use SchedulingTerms\App\Core\Routing\RoutesGenerator;
use SchedulingTerms\App\Utils\Config;
use Slim\App;

abstract class Kernel
{
    protected static array $middlewares = [];
    protected static array $globalMiddlewares = [];

    public function __construct(
        private readonly App $app,
        private readonly ContainerInterface $container,
        private readonly string $namespace,
        private readonly string $baseAppPath,
        private readonly string $controllersPath,
        private readonly string $routePath,
    ) {
    }

    private function setup(LoggerInterface $logger = null): void
    {
        $this->app->addRoutingMiddleware();
        $this->app->addBodyParsingMiddleware();

        $errorMiddleware = $this->app->addErrorMiddleware(
            true,
            true,
            true
        );

        $errorMiddleware->setDefaultErrorHandler(
            new AppErrorHandler(
                $this->app->getCallableResolver(),
                $this->app->getResponseFactory(),
                $logger
            )
        );

        if (!empty(static::$globalMiddlewares)) {
            foreach (static::$globalMiddlewares as $m) {
                $this->app->add($m);
            }
        }
    }

    public function run(): void
    {
        $this->setup($this->container->get(Logger::class));

        $routerGenerator = new RoutesGenerator(
            $this->app,
            $this->container->get(Config::class),
            static::$middlewares,
            $this->namespace,
            $this->baseAppPath,
            $this->controllersPath,
            $this->routePath,
        );

        $routerGenerator->generate();

        $this->app->run();
    }
}