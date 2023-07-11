<?php

namespace SchedulingTerms\App\Core\Loaders;

use DI\Container;
use DI\ContainerBuilder;

class DILoader
{
    private function getAllDefinitions(string $path): array {
        $files = scandir($path);
        $arr = [];

        foreach ($files as $file) {
            if(preg_match('/\.php/', $file)) {
                $data = require $path . '/' . $file;
                $defs = array_merge($arr, $data);
            }
        }

        return $arr;
    }

    public function load($path = __DIR__ . '/../../../di'): Container {
        $builder = new ContainerBuilder();

        $builder->useAutowiring(true);
        $builder->useAttributes(true);

        return $builder->addDefinitions($this->getAllDefinitions($path))->build();
    }
}