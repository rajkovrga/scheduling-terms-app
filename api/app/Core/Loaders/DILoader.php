<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Core\Loaders;

use CompiledContainer;
use DirectoryIterator;
use SplFileInfo;
use SchedulingTerms\App\Utils\Config;
use DI\Container;
use DI\ContainerBuilder;

final class DILoader
{
    public Container $container;

    public function __construct(private Config $config, private string $baseDirectory)
    {
        $compilationPath = $this->baseDirectory . DIRECTORY_SEPARATOR . 'compiled_container';
        $env = $this->config->get('app.environment', 'production');
        if ($env === 'production' && file_exists($path = $compilationPath . DIRECTORY_SEPARATOR . 'CompiledContainer.php')) {
            $this->loadCompiled($path);
        } else {
            $this->builder($env, $compilationPath, $this->config);
        }
    }

    private function load(ContainerBuilder $builder): void
    {
        $diDirectory = $this->baseDirectory . DIRECTORY_SEPARATOR . 'di';
        $dirIterator = new DirectoryIterator($diDirectory);

        /** @var SplFileInfo $entry */
        foreach ($dirIterator as $entry) {
            if ($entry->getExtension() === 'php') {
                $item = require_once $diDirectory . DIRECTORY_SEPARATOR . $entry->getFilename();
                $builder->addDefinitions($item);
            }
        }
    }

    public function loadCompiled(string $path): void
    {
        require_once $path;
        $this->container = new CompiledContainer();
    }

    public function builder(string $env, string $compilationPath, Config $config): void
    {
        $builder = new ContainerBuilder();
        $builder->useAttributes(true);
        $builder->useAutowiring(true);
        $builder->addDefinitions([Config::class => static fn() => $config]);

        if ($env === 'production') {
//            $builder->writeProxiesToFile(true);
//            $builder->enableDefinitionCache(); // apcu
            $builder->enableCompilation($compilationPath);
        }

        $this->load($builder);
        $this->container = $builder->build();
    }


}