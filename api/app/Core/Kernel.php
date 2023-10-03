<?php

namespace SchedulingTerms\App\Core;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Slim\App;

class Kernel
{
    protected array $middlewares = [];
    protected array $globalMiddlewares = [];

    public function __construct(
        private readonly App $app,
        private readonly Container $container
    )
    {
    }

    private function setup(LoggerInterface $logger = null): void {
        $this->app->addRoutingMiddleware();
        $this->app->addBodyParsingMiddleware();

        $errorMiddleware = $this->app->addErrorMiddleware(
            true,
            true,
            true
        );

        $errorMiddleware->setDefaultErrorHandler(new AppErrorHandler(
            $this->app->getCallableResolver(),
            $this->app->getResponseFactory(),
            $logger
        ));

        if (!empty($this->globalMiddlewares)) {
            foreach ($this->globalMiddlewares as $m) {
                $this->app->add($m);
            }
        }
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function run(): void
    {
        $this->setup($this->container->get(Logger::class));
        
        require_once __DIR__ . '/../../routes/routes.php';
        
        $this->app->run();
    }
}