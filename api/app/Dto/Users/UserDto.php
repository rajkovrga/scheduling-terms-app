<?php

namespace SchedulingTerms\App\Dto\Users;

use SchedulingTerms\App\Dto\BaseDto;

class UserDto
{
    public readonly string $name;
    public readonly string $email;
    public string $password;
    public string $createdAt;
    public string $updatedAt;
    public string $companyId;
    public string $roleId;
}