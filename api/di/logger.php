<?php

use DI\Container;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

return [
    Logger::class => static function(Container $container) {
        $logger = new Logger('logs');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/logs.log'));
        return $logger;
    }
];