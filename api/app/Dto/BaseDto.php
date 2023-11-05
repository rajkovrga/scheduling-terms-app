<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Dto;

use Carbon\CarbonImmutable;
use DateTimeInterface;

abstract class BaseDto
{
    protected function parseDateTime(string $key): ?CarbonImmutable
    {
        return array_key_exists($key, $this->data) && $this->data[$key] !== null ? CarbonImmutable::createFromFormat(
            static::dateFormat(),
            $this->data[$key]
        )->shiftTimezone('UTC') : null;
    }
    
    public static function dateFormat(): string
    {
        return DateTimeInterface::ATOM;
    }
}