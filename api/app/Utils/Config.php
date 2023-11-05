<?php
declare(strict_types=1);
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
        $configDirectory = $this->baseDirectory . DIRECTORY_SEPARATOR . 'config';

        $dirIterator = new DirectoryIterator($configDirectory);

        if (self::env('APP_ENVIRONMENT', 'production') !== 'production') {
            $dotenv = Dotenv::createImmutable([$this->baseDirectory]);
            $values = $dotenv->load();
            self::$envs = $values;
        }

        /** @var SplFileInfo $entry */
        foreach ($dirIterator as $entry) {
            if ($entry->getExtension() === 'php') {
                $item = require $configDirectory . DIRECTORY_SEPARATOR . $entry->getFilename();
                $this->values[$entry->getBasename('.php')] = $item;
            }
        }
    }

    public function get(string $cfg = '', mixed $default = null): mixed
    {
        if (empty($cfg)) {
            return $this->values;
        }

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