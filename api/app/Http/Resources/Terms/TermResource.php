<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Http\Resources\Terms;

use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Http\Resources\Companies\CompanyResource;
use SchedulingTerms\App\Http\Resources\Jobs\JobResource;
use SchedulingTerms\App\Http\Resources\Resource;
use SchedulingTerms\App\Http\Resources\Users\UserResource;
use SchedulingTerms\App\Models\Term;

/**
 * @template-extends Term
 */
class TermResource extends Resource
{
    
    public function toArray(ServerRequestInterface $request): array
    {
        $user = new UserResource($this->item->user);
        $job = new JobResource($this->item->job);
        $company = new CompanyResource($this->item->company);
        
        return [
            'id' => $this->item->id,
            'start_date' => $this->item->startDate,
            'end_date' => $this->item->endDate,
            'user' => $user->toArray($request),
            'job' => $job->toArray($request),
            'company' => $company->toArray($request)
        ];
    }
}