<?php

namespace SchedulingTerms\App\Http\Resources\Jobs;

use Psr\Http\Message\ServerRequestInterface as Request;
use SchedulingTerms\App\Http\Resources\Companies\CompanyResource;
use SchedulingTerms\App\Http\Resources\Resource;
use SchedulingTerms\App\Models\Job;

/**
 * @template-extends Job
 */
class JobResource extends Resource
{
    
    public function toArray(Request $request): array
    {
        $company = new CompanyResource($this->item->company);
        return [
            'id' => $this->item->id,
            'name' => $this->item->name,
            'created_at' => $this->item->createdAt,
            'updated_at' => $this->item->updatedAt,
            'company' => $company->toArray($request)
        ];
    }
}