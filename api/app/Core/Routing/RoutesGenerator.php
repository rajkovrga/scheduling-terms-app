<?php

namespace SchedulingTerms\App\Core\Routing;

use DirectoryIterator;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use SchedulingTerms\App\Core\Routing\Attributes\DeleteRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GetRoute;
use SchedulingTerms\App\Core\Routing\Attributes\GroupRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PostRoute;
use SchedulingTerms\App\Core\Routing\Attributes\PutRoute;
use SchedulingTerms\App\Exceptions\MiddlewareException;
use SchedulingTerms\App\Kernel;

class RoutesGenerator extends Kernel
{
    public function getControllers(string $dir, array $result = []): array
    {
        $dirIterator = new DirectoryIterator($dir);
        foreach ($dirIterator as $entry) {
            if (preg_match('/Controller.php$/', $entry->getRealPath())) {
                if ($entry->isDir()) {
                    $this->getControllers($entry->getRealPath());
                    continue;
                }
                
                $result[] = $entry->getRealPath();
            }
        }
        
        return $result;
    }
    
    /**
     * @throws ReflectionException|MiddlewareException
     */
    public function generateRoutes(string $relativePath = '/../../Controllers'): string
    {
        $controllers = $this->getControllers(__DIR__ . $relativePath);
        $content = '';
        foreach ($controllers as $controller) {
            $controller = substr($controller, 0, -4);
            $namespace = str_replace("Core\Routing", "", __NAMESPACE__) . str_replace('/', '\\', explode('app/', $controller)[1]);
            $class = new ReflectionClass(
                $namespace
            );
            
            $attributes = $class->getAttributes(GroupRoute::class);
            $methods = $class->getMethods();
            if (count($attributes) > 0) {
                $content .= $this->generateGroupRegisterGroup($attributes[0]->getArguments(), $methods, $namespace);
                continue;
            }
            $content .= $this->generateRegisterRoute($methods, $namespace);
        }
        
        return $content;
    }
    
    
    /**
     * @throws MiddlewareException
     */
    public function setMiddlewares(array $middlewares
    ): string
    {
        $content = '';
        foreach ($middlewares as $middleware) {
            if (empty($this->middlewares[$middleware]) || !isset($this->middlewares[$middleware])) {
                throw new MiddlewareException();
            }
            $content .=
                "->add({$this->middlewares[$middleware]}::class)";
        }
    
        return $content;
    }
    
    /**
     * @throws MiddlewareException
     */
    public function generateGroupRegisterGroup(
        array  $args,
        array  $methods,
        string $namespace
    ): string
    {
        
        $groupRoutes =
            $this->generateRegisterRoute($methods, $namespace, true);
        
        $content = <<<PHP
            \$this->app->group(
            '/{$args[0]}',
            function(RouteCollectorProxy \$grp) { {$groupRoutes} }
        )
        PHP;
        
        $content .= $this->setMiddlewares($args[1] ?? []) . ';';
        
        return $content;
    }
    
    /**
     * @throws MiddlewareException
     */
    public function generateRegisterRoute(
        array               $methods,
        string              $namespace,
        bool $isGroup = false
    ): string
    {
        $content = '';
        /** @var ReflectionMethod $method */
        foreach ($methods as $method) {
            $attributes = $method->getAttributes();
            foreach ($attributes as $attribute) {
                $requestType = match (true) {
                    $attribute->getName() == PostRoute::class => 'post',
                    $attribute->getName() == DeleteRoute::class => 'delete',
                    $attribute->getName() == GetRoute::class => 'get',
                    $attribute->getName() == PutRoute::class => 'put'
                };
                
                $startOfRoute = $isGroup ? '$grp' : '$this->app';
    
                $content .= <<<PHP
                            {$startOfRoute}->{$requestType}('{$attribute->getArguments()[0]}', ['{$namespace}', '{$method->getName()}'])
                        PHP;
                
                $content .= $this->setMiddlewares($attribute->getArguments()[1] ?? []) . ';';
            }
        }
        return $content;
    }
}