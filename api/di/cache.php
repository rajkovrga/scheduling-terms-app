<?php
declare(strict_types=1);

use Carbon\CarbonInterval;

return [
    'company' => [
        'prefix' => 'companies',
        'duration' => CarbonInterval::hours(8),
    ],
    'term' => [
        'prefix' => 'terms',
        'duration' => CarbonInterval::hours(8),
    ],
    'job' => [
        'prefix' => 'jobs',
        'duration' => CarbonInterval::hours(8),
    ],
    'user' => [
        'prefix' => 'users',
        'duration' => CarbonInterval::hours(8),
    ],
    'token' => [
        'prefix' => 'tokens',
        'duration' => CarbonInterval::month(8),
    ],
    'permission' => [
        'prefix' => 'permissions',
        'duration' => CarbonInterval::month(8),
    ],
    'recovery' => [
        'prefix' => 'recovery',
        'duration' => CarbonInterval::minutes(45),
    ],
    'csrf' => [
        'prefix' => 'csrf',
        'duration' => CarbonInterval::minutes(30),
    ],
];