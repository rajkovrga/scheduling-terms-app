<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Dto;

use Carbon\CarbonImmutable;
use DateTimeInterface;

abstract class BaseDto
{
    protected function parseDateTime(string $data): ?CarbonImmutable
    {
        return CarbonImmutable::createFromFormat(
            static::dateFormat(),
            $data
        )->shiftTimezone('UTC');
    }
    
    public static function dateFormat(): string
    {
        return DateTimeInterface::ATOM;
    }
}