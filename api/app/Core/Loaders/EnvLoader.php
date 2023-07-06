<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Core\Loaders;

use Dotenv\Dotenv;

class EnvLoader
{
    public function load(string $path = __DIR__ . '/../../../configurations'): void
    {
        $files = array_diff(scandir($path), ['..','.']);
        $dotenv = Dotenv::createUnsafeMutable($path, $files);
        $dotenv->load();
    }
}