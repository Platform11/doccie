<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AccountSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\AdministrationSeeder;
use Database\Seeders\ApiConsumerSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AccountSeeder::class,
            UserSeeder::class,
            AdministrationSeeder::class,
            ApiConsumerSeeder::class,
        ]);
    }
}
