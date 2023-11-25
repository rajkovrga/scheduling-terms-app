<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Http\Resources\Companies;

use SchedulingTerms\App\Http\Resources\Resource;
use SchedulingTerms\App\Models\Company;

class CompanyResource extends Resource
{
    /**
     * @param Company $item
     * @return array
     */
    public function toArray($item): array
    {
        return [
            'id' => $item?->id,
            'name' => $item?->name,
            'created_at' => $item?->createdAt,
            'updated_at' => $item?->updatedAt
        ];
    }
}