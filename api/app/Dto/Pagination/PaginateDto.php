<?php

namespace SchedulingTerms\App\Dto\Pagination;

/**
 * @template T
 */
class PaginateDto
{
    private ?string $cursor = null;
    /**
     * @var array<T>
     */
    private array $data = [];
}