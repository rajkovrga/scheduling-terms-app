<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Http\Resources\Terms;

use Psr\Http\Message\ServerRequestInterface;
use SchedulingTerms\App\Dto\Terms\TermDto;
use SchedulingTerms\App\Http\Resources\Companies\CompanyResource;
use SchedulingTerms\App\Http\Resources\Jobs\JobResource;
use SchedulingTerms\App\Http\Resources\Resource;
use SchedulingTerms\App\Http\Resources\Users\UserResource;

/**
 * @extends Resource<TermDto>
 */
class TermResource extends Resource
{
    
    public function toArray(ServerRequestInterface $request): array
    {
        return [
            'id' => $this->item->id,
            'name' => $this->item->name,
            'created_at' => $this->item->createdAt,
            'updated_at' => $this->item->updatedAt,
            'start_date' => $this->item->startDate,
            'end_date' => $this->item->endDate,
            'user' => new UserResource($this->item->user),
            'job' => new JobResource($this->item->job),
            'company' => new CompanyResource($this->item->company)
        ];
    }
}