<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Http\Resources\Companies;

use Psr\Http\Message\ServerRequestInterface as Request;
use SchedulingTerms\App\Dto\Companies\CompanyDto;
use SchedulingTerms\App\Http\Resources\Resource;

/**
 * @extends Resource<CompanyDto>
 */
class CompanyResource extends Resource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->item->id,
            'name' => $this->item->name,
            'created_at' => $this->item->createdAt->toDateTimeString(),
            'updated_at' => $this->item->updatedAt->toDateTimeString()
        ];
    }
}