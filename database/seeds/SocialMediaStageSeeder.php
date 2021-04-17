<?php

use Illuminate\Database\Seeder;
use App\SocialMediaStage;

class SocialMediaStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stages = [
            "Proposal",
            "Onboarding",
            "Managing",
        ];

        foreach ($stages as $index => $stage) {
            SocialMediaStage::create([
                'name' => $stage,
                'order' => $index,
            ]);
        }
    }
}
