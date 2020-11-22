<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $this->call('BlogIndustriesTableSeeder');
      $this->call('CreditRateTableSeeder');
      $this->call('ServicesSeeder');
      $this->call('UsersPermissionsSeeder');
      $this->call('PaymeentGatewaysTableSeeder');
    }
}
