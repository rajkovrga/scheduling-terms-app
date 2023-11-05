<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Dto\Users;

use Carbon\CarbonImmutable;
use SchedulingTerms\App\Dto\BaseDto;

class CreateUpdateUserDto extends BaseDto
{
    public function __construct(
        public readonly string $email,
        public readonly int $companyId
    )
    {
    }
}