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

        $this->table('roles')
            ->addColumn('name', 'string')
            ->create();

        $this->table('permissions')
            ->addColumn('name', 'string')
            ->create();

        $this->table('role_permission')
            ->addColumn('role_id', 'integer')
            ->addColumn('permission_id', 'integer')
            ->addForeignKey('role_id', 'roles', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('permission_id', 'permissions', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();

        $this->table('companies')
            ->addColumn('name', 'string')
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->create();

        $this->table('jobs')
            ->addColumn('name', 'string')
            ->addColumn('during', 'integer')
            ->addColumn('company_id', 'integer')
            ->addForeignKey('company_id', 'companies', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->create();

        $this->table('users')
            ->addColumn('name', 'string')
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
            ->addIndex(['name'])
            ->create();

            $this->table('terms')
            ->addColumn('user_id', 'integer')
            ->addColumn('start_date', 'date')
            ->addColumn('end_date', 'date')
            ->addColumn('job_id', 'integer')
            ->addColumn('company_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('job_id', 'jobs', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('company_id', 'companies', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();
    }
}
