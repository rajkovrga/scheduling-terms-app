<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Utils;

use DirectoryIterator;
use Dotenv\Dotenv;
use Error;
use SplFileInfo;

final class Config
{
    private array $values = [];

    private static array $envs;

    public function __construct(public string $baseDirectory)
    {
        $configDirectory = $this->baseDirectory . DIRECTORY_SEPARATOR . 'configurations';

        $dirIterator = new DirectoryIterator($configDirectory);

        if (self::env('APP_ENVIRONMENT', 'production') !== 'production') {
            $dotenv = Dotenv::createImmutable([$this->baseDirectory], fileEncoding: 'utf-8');
            $values = $dotenv->load();
            self::$envs = $values;
        }

        /** @var SplFileInfo $entry */
        foreach ($dirIterator as $entry) {
            if ($entry->getExtension() === 'php') {
                $item = require_once $configDirectory . DIRECTORY_SEPARATOR . $entry->getFilename();
                if ($item === true) {
                    throw new Error('File already loaded');
                }

                $this->values = array_merge($item, $this->values);
            }
        }
    }

    public function get(string $cfg, mixed $default = null): mixed
    {
        $values = explode('.', $cfg);
        $item = $this->values;

        foreach ($values as $value) {
            if (!array_key_exists($value, $item)) {
                return $default;
            }

            $item = $item[$value];
        }

        return $item;
    }

    public static function env(string $name, mixed $default = null): mixed
    {
        if (!isset(self::$envs)) {
            $val = getenv();

            if ($val === false) {
                throw new Error('Failed to load ENV variables');
            }

            self::$envs = $val;
        }

        if (array_key_exists($name, self::$envs)) {
            return self::$envs[$name];
        }

        return $default;
    }

}