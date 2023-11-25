<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Http\Resources\Terms;

use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Http\Resources\Companies\CompanyResource;
use SchedulingTerms\App\Http\Resources\Jobs\JobResource;
use SchedulingTerms\App\Http\Resources\Resource;
use SchedulingTerms\App\Http\Resources\Users\UserResource;
use SchedulingTerms\App\Models\Term;

class TermResource extends Resource
{
    
    /**
     * @param Term $item
     * @return array
     */
    public function toArray($item): array
    {
        $user = new UserResource($this->request);
        $job = new JobResource($this->request);
        $company = new CompanyResource($this->request);
        
        return [
            'id' => $item->id,
            'start_date' => $item->startDate,
            'end_date' => $item->endDate,
            'user' => $user->toArray($item->user),
            'job' => $job->toArray($item->job),
            'company' => $company->toArray($item->company)
        ];
    }
}