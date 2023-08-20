<?php
declare(strict_types=1);

use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class CompaniesSeeder extends AbstractSeed
{
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
        $faker = Factory::create();
        $format = 'Y-m-d\TH:i:sP';
        $this->execute('delete from companies');

        $companies = [];
        for ($i = 1; $i < 11; $i++) {
            $companies[] = [
                'id' => $i,
                'name' => $faker->company(),
                'created_at' => date($format),
                'updated_at' => date($format),
            ];
        }

        $table = $this->table('companies');

        $table->insert($companies)
            ->saveData();
    }

}
