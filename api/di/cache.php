<?php
declare(strict_types=1);

use Carbon\CarbonInterval;

return [
    'company' => [
        'cache_prefix' => 'clients',
        'cache_duration' => CarbonInterval::hours(2),
    ],
    'images' => [
        'cache_prefix' => 'images',
        'cache_duration' => CarbonInterval::hours(2),
    ],

];