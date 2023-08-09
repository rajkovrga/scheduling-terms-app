<?php

namespace SchedulingTerms\App\Dto\Pagination;

use SchedulingTerms\App\Dto\BaseDto;

/**
 * @template T
 */
class PaginateDto extends BaseDto
{
    private ?string $cursor = null;
    /**
     * @var array<T>
     */
    private array $data = [];
}