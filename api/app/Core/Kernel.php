<?php

namespace SchedulingTerms\App\Core;

use DI\Container;
use Slim\App;

class Kernel
{
    protected array $middlewares = [];
    protected array $globalMiddlewares = [];

    public function setup(): void {

    }

    private function getAllControllers(string $path = __DIR__ . '/../app/Controllers') {
        $controllers = array_diff(scandir($path), ['.', '..']);

        foreach ($controllers as $controller) {
            if(!is_dir($controller)) {

            } else if(realpath($controller)) {

            }
        }

    }

    public function routes(): void {

    }

    public function run(Container $container, App $app): void {

    }
}