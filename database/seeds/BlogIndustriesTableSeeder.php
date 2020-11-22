<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BlogIndustriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $industries = ["Art", "Automotive, Boats etc.", "Beverage, Beer, Liquor & Wine", "Business Services", "City & Government", "Clothing & Retail", "Community Centers & Hotels", "Contractors", "Education & Schools", "Events", "Groups, Social, Personal & Hobbies", "Hair & Beauty", "Health & Wellness", "Home Improvement", "Industrial & Technology", "National Products & Brands", "Outdoors", "Professional Services", "Restaurant & Food", "Sports & Entertainment", "Transportation"];

        foreach( $industries as $industry ) {
            DB::table('blog_industries')->insert([
                [
                    'name'          => $industry,
                    'created_at'    => Carbon::now()
                ],
            ]);
        }
    }
}
