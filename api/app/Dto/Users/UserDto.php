<?php

namespace SchedulingTerms\App\Dto\Users;

use SchedulingTerms\App\Dto\BaseDto;

class UserDto extends BaseDto
{
    private string $name;
    private string $email;
    private string $password;
    private string $createdAt;
    private string $updatedAt;
    private string $companyId;
    private string $roleId;
}