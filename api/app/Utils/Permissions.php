<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Utils;

abstract class Permissions
{

    // Users
    public const ViewAllUsers = 'view all users';
    public const ViewUsers = 'view users';
    public const CreateUser = 'create users';
    public const CreateEmployeeUser = 'create employee user';
    public const EditUser = 'edit user';
    public const EditOwnUser = 'edit own user';
    public const DeleteOwnUser = 'delete own user';
    public const DeleteUser = 'delete user';

    // Jobs
    public const ViewAllJobs = 'view all jobs';
    public const ViewJobs = 'view jobs';
    public const CreateJob = 'create job';
    public const EditJob = 'edit job';
    public const DeleteJob = 'delete job';

    // Terms
    public const ViewTerms = 'view terms';
    public const ViewSelfTerms = 'view self terms';

    public const CreateTerm = 'create term';
    public const EditTerm = 'edit term';
    public const DeleteTerm = 'delete term';


    // Companies
    public const ViewAllCompanies = 'view all companies';
    public const CreateCompany = 'create company';
    public const EditCompany = 'edit company';
    public const DeleteCompany = 'delete company';
    public const EditSelfCompany = 'edit company';

    // Profile

    public const EditProfile = 'edit profile';

    public static function all(): array
    {
        return [
            self::ViewAllUsers,
            self::ViewUsers,
            self::CreateUser,
            self::EditUser,
            self::EditOwnUser,
            self::DeleteOwnUser,
            self::DeleteUser,
            self::ViewAllJobs,
            self::ViewJobs,
            self::CreateJob,
            self::EditJob,
            self::DeleteJob,
            self::ViewAllCompanies,
            self::CreateCompany,
            self::EditCompany,
            self::DeleteCompany,
            self::CreateEmployeeUser,
            self::EditSelfCompany,
            self::CreateTerm,
            self::EditTerm,
            self::DeleteTerm,
            self::ViewTerms,
            self::EditProfile
        ];
    }
}