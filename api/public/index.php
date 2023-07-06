<?php

use SchedulingTerms\App\Core\Loaders\EnvLoader;

require_once __DIR__ . '/../vendor/autoload.php';

(new EnvLoader())->load();