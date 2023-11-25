<?php

namespace SchedulingTerms\App\Http\Resources\Jobs;

use Psr\Http\Message\ServerRequestInterface as Request;
use SchedulingTerms\App\Http\Resources\Companies\CompanyResource;
use SchedulingTerms\App\Http\Resources\Resource;
use SchedulingTerms\App\Models\Job;

class JobResource extends Resource
{
    
    /**
     * @param  Job $item
     * @return array
     */
    public function toArray($item): array
    {
        $company = new CompanyResource($this->request);
        return [
            'id' => $item->id,
            'name' => $item->name,
            'created_at' => $item->createdAt,
            'updated_at' => $item->updatedAt,
            'company' => $company->toArray($item->company)
        ];
    }
}