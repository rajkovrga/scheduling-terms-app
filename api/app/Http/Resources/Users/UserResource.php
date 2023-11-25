<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Http\Resources\Users;

use Psr\Http\Message\ServerRequestInterface as Request;
use SchedulingTerms\App\Http\Resources\Companies\CompanyResource;
use SchedulingTerms\App\Http\Resources\Resource;
use SchedulingTerms\App\Models\User;

class UserResource extends Resource
{
    /**
     * @param User $item
     * @return array
     */
    public function toArray($item): array
    {
        $company = new CompanyResource($this->request);
        
        return [
            'id' => $item->id,
            'email' => $item->email,
            'created_at' => $item->createdAt,
            'updated_at' => $item->updatedAt,
            'company' => $company->toArray($item->company),
        ];
    }
}