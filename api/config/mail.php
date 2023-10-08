<?php

declare(strict_types=1);

use SchedulingTerms\App\Utils\Config;

return [
  'smtp' => [
      'password' => Config::env('MAIL_PASSWORD'),
      'username' => Config::env('MAIL_USERNAME'),
      'email' => Config::env('MAIL_USERNAME')
  ]
];