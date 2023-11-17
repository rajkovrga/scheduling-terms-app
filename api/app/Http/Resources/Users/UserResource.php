<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Http\Resources\Users;

use Psr\Http\Message\ServerRequestInterface as Request;
use SchedulingTerms\App\Http\Resources\Companies\CompanyResource;
use SchedulingTerms\App\Http\Resources\Resource;
use SchedulingTerms\App\Models\User;

/**
 * @template-extends User
 */
class UserResource extends Resource
{
    public function toArray(Request $request): array
    {
        $company = new CompanyResource($this->item->company);
        
        return [
            'id' => $this->item->id,
            'email' => $this->item->email,
            'created_at' => $this->item->createdAt,
            'updated_at' => $this->item->updatedAt,
            'company' => $company->toArray($request),
        ];
    }
}