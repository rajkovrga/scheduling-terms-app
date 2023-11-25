<?php

namespace SchedulingTerms\App\Http\Resources\Pagination;

use SchedulingTerms\App\Http\Resources\Resource;

class PaginationResource extends Resource
{
    
    /**
     * @param array $item
     * @return array
     */
    public function toArray($item): array
    {
        $lastElement = end($item);
        $nextCursor = $lastElement ? $lastElement['id'] : null;
        return [
            'data' => $item,
            'next_cursor' => $nextCursor
        ];
    }
}