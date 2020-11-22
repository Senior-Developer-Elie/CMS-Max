<?php

use Illuminate\Database\Seeder;

class StagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stages = [
            [
                'name' => 'Quoting',
                'priority' => 1,
            ],
            [
                'name' => 'Gathering Information',
                'priority' => 2,
            ],
            [
                'name' => 'Home Page Content',
                'priority' => 3,
            ],
            [
                'name' => 'Design',
                'priority' => 4,
            ],
            [
                'name' => 'Website Core Setup',
                'priority' => 5,
            ],
            [
                'name' => 'Inner Page Setup',
                'priority' => 6,
            ],
            [
                'name' => 'Final Checks',
                'priority' => 7,
            ],
            [
                'name' => 'Go Live!',
                'priority' => 8,
            ],
            [
                'name' => 'Colletions',
                'priority' => 9,
            ],
            [
                'name' => 'Completed',
                'priority' => 10,
            ]
        ];

        foreach ($stages as $stage) {
            \App\Stage::create($stage);
        }
    }
}
