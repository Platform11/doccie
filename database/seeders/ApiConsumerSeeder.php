<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApiConsumerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('api_consumers')->insert([
            'name' => 'postmark',
            'api_token' => 'QK5QZCPQ8xLEfzITX6UlwOs4SVKAjMZ1V2IaiYmVmhm7YbLSffoCtEw005uW'
        ]);
    }
}
