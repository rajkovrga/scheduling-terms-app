<?php

namespace SchedulingTerms\App\Http\Resources\Jobs;

use Psr\Http\Message\ServerRequestInterface as Request;
use SchedulingTerms\App\Dto\Jobs\JobDto;
use SchedulingTerms\App\Http\Resources\Companies\CompanyResource;
use SchedulingTerms\App\Http\Resources\Resource;

/**
 * @template-extends JobDto
 */
class JobResource extends Resource
{
    
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->item->id,
            'name' => $this->item->name,
            'created_at' => $this->item->createdAt,
            'updated_at' => $this->item->updatedAt,
            'company' => new CompanyResource($this->item->company)
        ];
    }
}