<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddTokenTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $roles = $this->table('tokens', ['id' => false, 'primary_key' => 'id']);
        $roles
            ->addColumn('id', 'biginteger', ['identity' => true, 'signed' => false])
            ->addColumn('token', 'string')
            ->addColumn('user_id', 'integer')
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();
    }
}
