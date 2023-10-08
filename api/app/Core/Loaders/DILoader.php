<?php

declare(strict_types=1);

namespace SchedulingTerms\App\Core\Loaders;

use CompiledContainer;
use DirectoryIterator;
use Psr\Container\ContainerInterface;
use SchedulingTerms\App\Utils\Config;
use SplFileInfo;
use DI\Container;
use DI\ContainerBuilder;

final class DILoader implements ContainerInterface
{
    private readonly ContainerInterface $container;

    public function __construct(
        private readonly string $baseDirectory,
    ) {
        $compilationPath = $this->baseDirectory . DIRECTORY_SEPARATOR . 'compiled_container';
        $env = getenv('APP_ENVIRONMENT');
        $path = $compilationPath . DIRECTORY_SEPARATOR . 'CompiledContainer.php';
        $this->container = match ($env === 'production' && file_exists($path)) {
            true => $this->loadCompiled($path),
            false => $this->builder($env, $compilationPath),
        };
    }

    private function load(ContainerBuilder $builder): void
    {
        $diDirectory = $this->baseDirectory . DIRECTORY_SEPARATOR . 'di';
        $dirIterator = new DirectoryIterator($diDirectory);

        /** @var SplFileInfo $entry */
        foreach ($dirIterator as $entry) {
            if ($entry->getExtension() === 'php') {
                $item = require $diDirectory . DIRECTORY_SEPARATOR . $entry->getFilename();
                $builder->addDefinitions($item);
            }
        }
    }

    public function loadCompiled(string $path): ContainerInterface
    {
        require_once $path;
        return new CompiledContainer();
    }

    private function builder(string $env, string $compilationPath): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $builder->useAttributes(true);
        $builder->useAutowiring(true);
        $builder->addDefinitions([
            Config::class => static fn() => new Config(APP_BASE_PATH)
        ]);

        if ($env === 'production') {
            $builder->enableDefinitionCache(); // apcu
            $builder->enableCompilation($compilationPath);
        }

        $this->load($builder);
        return $builder->build();
    }

    public function getContainer(): Container
    {
        return $this->container;
    }


    public function get(string $id)
    {
        return $this->container->get($id);
    }

    public function has(string $id): bool
    {
        return $this->container->has($id);
    }
}