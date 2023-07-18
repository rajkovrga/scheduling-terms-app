<?php

namespace SchedulingTerms\App\Core;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Exceptions\MiddlewareException;
use Slim\App;
use Slim\Interfaces\RouteGroupInterface;
use Slim\Routing\Route;
use Slim\Routing\RouteCollectorProxy;

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

    private function getControllers(string $dir = __DIR__ . '/../Controllers', array &$result = []): array {
        $controllers = array_diff(scandir($dir), ['.', '..']);

        foreach ($controllers as $controller) {
            $path = realpath($dir . '/' . $controller);
            if(is_dir($path)) {
                $this->getControllers($path);
                continue;
            }

            $result[] = $path;
        }

        return $result;
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function routes(): Kernel
    {
        $controllers = $this->getControllers();
        foreach ($controllers as $controller) {
            if (preg_match('/Controller.php$/', $controller)) {
                $controller = substr($controller, 0, -4);
                $namespace = str_replace("Core", "", __NAMESPACE__) . str_replace('/', '\\', explode('app/', $controller)[1]);
                $class = new ReflectionClass(
                    $namespace
                );

                $attributes = $class->getAttributes(GroupRoute::class);
                $methods = $class->getMethods();
                if (count($attributes) > 0) {
                    $args = $attributes[0]->getArguments();

                    $group = $this->app->group(
                        $args[0],
                        fn(RouteCollectorProxy $group) => $this->registerRoute($methods, $namespace, $group)
                    );

                    $this->setMiddlewares($args[1] ?? [], $group);

                    continue;
                }
                $this->registerRoute($methods, $namespace, $this->app, $args[1] ?? []);
            }
        }

        return $this;
    }


    public function run(): void
    {
        $this->setup($this->container->get(Logger::class));
        $this->app->run();
    }

    /**
     * @throws MiddlewareException
     */
    private function setMiddlewares(array $middlewares, RouteGroupInterface|Route $group): void
    {
        foreach ($middlewares as $middleware) {
            if (empty($this->middlewares[$middleware]) || !isset($this->middlewares[$middleware])) {
                throw new MiddlewareException();
            }
            $group->add($this->middlewares[$middleware]);
        }
    }

    /**
     * @throws MiddlewareException
     */
    private function registerRoute(
        array               $methods,
        string              $namespace,
        RouteCollectorProxy $group,
        array               $middlewares = []
    ): void
    {
        foreach ($methods as $method) {
            $attributes = $method->getAttributes();
            foreach ($attributes as $attribute) {
                $requestType = match ($attribute->getName()) {
                    PostRoute::class => 'post',
                    DeleteRoute::class => 'delete',
                    GetRoute::class => 'get',
                    PutRoute::class => 'put',
                };

                $grp = $group->{$requestType}($attribute->getArguments()[0], [$namespace, $method->getName()]);
                var_dump($group);
                $this->setMiddlewares($middlewares, $grp);

            }
        }
    }

}