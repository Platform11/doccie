<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        Account::create(['name' => 'One Accountants & Bedrijsadviseurs', 'twinfield_office_code' => 'one']);
        Account::create(['name' => 'Syfers Administratie & Advies Bleiswijk B.V.', 'twinfield_office_code' => 'syfers-advies']);
    }
}
