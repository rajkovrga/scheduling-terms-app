<?php

use Phinx\Seed\AbstractSeed;
use SchedulingTerms\App\Utils\Permissions;
use SchedulingTerms\App\Utils\Roles;

class RoleAndPermissionSeeder extends AbstractSeed
{
    private array $superAdminPermissions = [
        Permissions::EditProfile,
        Permissions::CreateCompany,
        Permissions::EditCompany,
        Permissions::DeleteCompany,
        Permissions::ViewAllUsers,
        Permissions::ViewAllCompanies
    ];
    private array $adminPermissions = [
        Permissions::EditProfile,
        Permissions::ViewAllJobs,
        Permissions::CreateJob,
        Permissions::EditJob,
        Permissions::DeleteJob,
        Permissions::CreateEmployeeUser
    ];
    private array $userPermissions = [
        Permissions::EditProfile,
        Permissions::DeleteTerm,
        Permissions::EditTerm,
        Permissions::CreateTerm,
        Permissions::ViewSelfTerms
    ];

    private array $employeePermissions = [
        Permissions::EditProfile,
        Permissions::ViewTerms,
        Permissions::ViewJobs
    ];

    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $this->adapter->execute("delete from role_permission");
        $this->adapter->execute("delete from permissions");
        $this->adapter->execute("delete from roles");

        $rolePermissions = [
            Roles::SuperAdmin => $this->superAdminPermissions,
            Roles::Admin => $this->adminPermissions,
            Roles::Employee => $this->employeePermissions,
            Roles::User => $this->userPermissions,
        ];

        $rolesData = [];
        foreach (array_keys($rolePermissions) as $role) {
            $rolesData[] = ['name' => $role];
        }
        $this->table('roles')->insert($rolesData)->saveData();

        $permissions = Permissions::all();
        $permissionsData = [];

        foreach ($permissions as $permission) {
            $permissionsData[] = ['name' => $permission];
        }
        $this->table('permissions')->insert($permissionsData)->saveData();

        $rolePermissionData = [];
        foreach ($rolePermissions as $role => $permissions) {
            $roleId = $this->fetchRow("SELECT id FROM roles WHERE name = '" . $role . "'")['id'];
            foreach ($permissions as $permission) {
                $permissionId = $this->fetchRow("SELECT id FROM permissions WHERE name = '" . $permission . "'")['id'];
                if($permissionId !== null)
                    $rolePermissionData[] = ['role_id' => $roleId, 'permission_id' => $permissionId];
            }
        }

        $this->table('role_permission')->insert($rolePermissionData)->saveData();

    }
}
