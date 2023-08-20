<?php
declare(strict_types=1);

use Faker\Factory;
use Phinx\Seed\AbstractSeed;

class JobsSeeder extends AbstractSeed
{

    public function getDependencies(): array
    {
        return [
            'CompaniesSeeder'
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
        $faker = Factory::create();
        $format = 'Y-m-d\TH:i:sP';
        $jobs = [];
        $companies = $this->fetchAll('SELECT * FROM companies');
//        $ids = array_map(function($item) {
//            return $item['id'];
//        }, $companies);

        for ($i = 1; $i < 200; $i++) {
            $jobs[] = [
                'id' => $i,
                'name' => $faker->jobTitle(),
                'during' => rand(10, 120),
                'company_id' => rand(1,10),
                'created_at' => date($format),
                'updated_at' => date($format),
            ];
        }

        $table = $this->table('jobs');
        $this->execute("delete from jobs");

        $table->insert($jobs)
            ->saveData();
    }
}
