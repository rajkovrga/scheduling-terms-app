<?php
declare(strict_types=1);

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Faker\Factory;
use Phinx\Seed\AbstractSeed;
use SchedulingTerms\App\Utils\Roles;

class UserSeeder extends AbstractSeed
{

    public function getDependencies(): array
    {
        return [
            CompaniesSeeder::class,
            RoleAndPermissionSeeder::class
        ];
    }
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
        $this->execute('delete from users');

        $faker = Factory::create();
        $superAdminId = $this->adapter->fetchRow("SELECT * FROM roles where name = '". Roles::SuperAdmin ."'")['id'];

        $users[] = [
            'email' => 'rajko@vrga.dev',
            'password' => password_hash('password', PASSWORD_ARGON2I),
            'company_id' => null,
            'role_id' => $superAdminId
        ];

        $roles = $this->adapter->fetchAll('SELECT * FROM roles');
        $roleIds = array_map(function($item) {
            return $item['id'];
        }, $roles);
        
        for ($i = 2; $i < 100; $i++) {
            $users[] = [
                'email' => $faker->email,
                'password' =>  password_hash('password', PASSWORD_ARGON2I),
                'company_id' => rand(1,10),
                'role_id' => $roleIds[array_rand($roleIds)]
            ];
        }
        $table = $this->table('users');
        $table->insert($users)
            ->saveData();
    }
}