<?php
declare(strict_types=1);

use SchedulingTerms\App\Utils\Config;

return [
    'environment' => Config::env('APP_ENVIRONMENT', 'production'),
    'minimalTimeInterval' => 15 //TODO: move in db
];
