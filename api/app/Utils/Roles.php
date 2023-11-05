<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Utils;

interface Roles
{
    public const Admin = 'Administrator';
    public const SuperAdmin = 'SuperAdmin';
    public const Employee = 'Company Manager';
    public const User = 'User';

}