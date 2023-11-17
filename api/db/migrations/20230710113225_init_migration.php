<?php
declare(strict_types=1);
use Phinx\Migration\AbstractMigration;

class InitMigration extends AbstractMigration
{

    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $roles = $this->table('roles', ['id' => false, 'primary_key' => 'id']);
        $roles
            ->addColumn('id', 'biginteger', ['identity' => true, 'signed' => false])
            ->addColumn('name', 'string')
            ->create();
    
        $permissions = $this->table('permissions', ['id' => false, 'primary_key' => 'id']);
        $permissions
            ->addColumn('id', 'biginteger', ['identity' => true, 'signed' => false])
            ->addColumn('name', 'string')
            ->create();
    
        $rolePermission = $this->table('role_permission', ['id' => true, 'primary_key' => 'id']);
        $rolePermission
            ->addColumn('role_id', 'integer')
            ->addColumn('permission_id', 'integer')
            ->addForeignKey('role_id', 'roles', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('permission_id', 'permissions', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();
    
        $companies = $this->table('companies', ['id' => false, 'primary_key' => 'id']);
        $companies
            ->addColumn('id', 'biginteger', ['identity' => true, 'signed' => false])
            ->addColumn('name', 'string')
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addIndex(['id'], ['unique' => true])  // Ovo će dodati unique constraint na 'id'
            ->create();
    
        $jobs = $this->table('jobs', ['id' => false, 'primary_key' => 'id']);
        $jobs
            ->addColumn('id', 'biginteger', ['identity' => true, 'signed' => false])
            ->addColumn('name', 'string')
            ->addColumn('during', 'integer')
            ->addColumn('company_id', 'integer')
            ->addForeignKey('company_id', 'companies', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->create();
    
        $users = $this->table('users', ['id' => false, 'primary_key' => 'id']);
        $users
            ->addColumn('id', 'biginteger', ['identity' => true, 'signed' => false])
            ->addColumn('email', 'string', ['limit' => 100,])
            ->addColumn('password', 'string')
            ->addColumn('company_id', 'integer', ['null' => true])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addColumn('role_id', 'integer')
            ->addForeignKey('company_id', 'companies', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('role_id', 'roles', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addIndex(['email'], ['unique' => true])
            ->addIndex(['password'])
            ->addIndex(['role_id'])
            ->create();
    
        $terms = $this->table('terms', ['id' => false, 'primary_key' => 'id']);
        $terms
            ->addColumn('id', 'biginteger', ['identity' => true, 'signed' => false])
            ->addColumn('user_id', 'integer')
            ->addColumn('start_date', 'datetime')
            ->addColumn('end_date', 'datetime')
            ->addColumn('job_id', 'integer')
            ->addColumn('company_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('job_id', 'jobs', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('company_id', 'companies', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();
    }
}
