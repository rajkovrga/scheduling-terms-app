<?php
declare(strict_types=1);

use Carbon\CarbonInterval;

return [
    'company' => [
        'cache_prefix' => 'companies',
        'cache_duration' => CarbonInterval::hours(8),
    ],
    'term' => [
        'cache_prefix' => 'terms',
        'cache_duration' => CarbonInterval::hours(8),
    ],
    'job' => [
        'cache_prefix' => 'jobs',
        'cache_duration' => CarbonInterval::hours(8),
    ],
    'user' => [
        'cache_prefix' => 'users',
        'cache_duration' => CarbonInterval::hours(8),
    ],
];