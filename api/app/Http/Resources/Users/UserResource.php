<?php
declare(strict_types=1);

namespace SchedulingTerms\App\Http\Resources\Users;

use Psr\Http\Message\ServerRequestInterface as Request;
use SchedulingTerms\App\Dto\Users\UserDto;
use SchedulingTerms\App\Http\Resources\Companies\CompanyResource;
use SchedulingTerms\App\Http\Resources\Resource;

/**
 * @extends Resource<UserDto>
 */
class UserResource extends Resource
{
    public function toArray(Request $data): array
    {
        return [
            'id' => $this->item->id,
            'email' => $this->item->email,
            'created_at' => $this->item->createdAt,
            'updated_at' => $this->item->updatedAt,
            'company' => new CompanyResource($this->item->company),
        ];
    }
}