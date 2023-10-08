<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Core\Routing;

use Error;
use FilesystemIterator;
use Generator;
use RecursiveDirectoryIterator;
use ReflectionClass;
use ReflectionMethod;
use RuntimeException;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Core\Routing\Attributes\Route;
use SchedulingTerms\App\Utils\Config;
use Slim\Routing\RouteCollectorProxy;
use SplFileInfo;
use SplFileObject;

readonly class RoutesGenerator
{
    private array $search;
    private array $replace;

    public function __construct(
        private RouteCollectorProxy $router,
        private Config $config,
        private array $middleware,
        private string $namespace,
        private string $baseAppBase,
        private string $controllersPath,
        private string $routePath,
    ) {
        $this->search = [
            $this->baseAppBase,
            DIRECTORY_SEPARATOR,
            '.php'
        ];

        $this->replace = ['', '\\', ''];
    }

    private function getControllersWithNamespace(string $dir): Generator
    {
        $dirIterator = new RecursiveDirectoryIterator(
            $dir,
            FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME | FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS | FilesystemIterator::OTHER_MODE_MASK
        );

        /**
         * @var SplFileInfo $file
         */
        foreach ($dirIterator as $file) {
            if ($file->isDir()) {
                continue;
            }

            $controller = $this->namespace . '\\' . str_replace($this->search, $this->replace, $file->getRealPath());
            yield $controller;
        }
    }

    /**
     * @param SplFileObject $file
     * @param string $controller
     * @param GroupRoute|null $groupRouteAttribute
     * @param array<ReflectionMethod> $methods
     * @return void
     */
    private function generateRoute(
        SplFileObject $file,
        string $controller,
        ?GroupRoute $groupRouteAttribute,
        array $methods
    ): void {
        if ($groupRouteAttribute !== null && $file->fwrite(
                "\$app->group(\"$groupRouteAttribute->path\", function(\$app) {\n"
            ) === false) {
            throw new RuntimeException('Failed to write to routes file');
        }

        foreach ($methods as $method) {
            $attributes = $method->getAttributes();

            foreach ($attributes as $attribute) {
                /** @var Route $instance */
                $instance = match ($attribute->getName()) {
                    GetRoute::class, DeleteRoute::class, PostRoute::class, PutRoute::class => $attribute->newInstance(),
                    default => null
                };

                $middlewares = implode("\n", iterator_to_array($this->generateMiddleware($instance)));

                $file->fwrite(
                    "\$app->{$instance->method->value}(\"$instance->path\", [\"$controller\", \"{$method->getName()}\"])$middlewares;\n"
                );
            }
        }


        if ($groupRouteAttribute !== null) {
            $middlewares = implode("\n", iterator_to_array($this->generateMiddleware($groupRouteAttribute)));

            $file->fwrite("})\n$middlewares;\n");
        }
    }

    private function generateMiddleware(Route|GroupRoute $instance): Generator
    {
        if (count($instance->middlewares) === 0 || count($this->middleware) === 0) {
            return [];
        }

        foreach ($instance->middlewares as $middleware) {
            if (!is_string($middleware)) {
                throw new Error('');
            }

            $md = match (array_key_exists($middleware, $this->middleware)) {
                true => $this->middleware[$middleware],
                false => $middleware,
            };

            yield "\n\t->add(\"$md\")";
        }
    }

    public function generate(): void
    {
        $app = $this->router;

        if ($this->config->get('app.environment') === 'production' && file_exists($this->routePath)) {
            require_once $this->routePath;
            return;
        }

        $file = new SplFileObject($this->routePath, 'wb', false);

        if ($file->fwrite(
                "<?php\n\ndeclare(strict_types=1);\n\n/** @var Slim\Routing\RouteCollectorProxy \$app */\n\n\n"
            ) === false) {
            throw new RuntimeException('Failed to write routes file header');
        }

        foreach ($this->getControllersWithNamespace($this->controllersPath) as $controller) {
            $reflex = new ReflectionClass($controller);
            $attributes = $reflex->getAttributes(GroupRoute::class);
            $methods = $reflex->getMethods();

            $this->generateRoute(
                $file,
                $controller,
                count($attributes) > 0 ? $attributes[0]->newInstance() : null,
                $methods
            );
        }

        if (!$file->fflush()) {
            throw new RuntimeException('Failed to flush routes file');
        }

        require_once $this->routePath;
    }

}