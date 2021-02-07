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
        Account::create(['name' => 'One Accountants & Bedrijsadviseurs', 'logo' => 'oneaccountants-logo.svg', 'twinfield_office_code' => 'one']);
        Account::create(['name' => 'Syfers Administratie & Advies Bleiswijk B.V.', 'logo' => 'syfers-logo.svg', 'twinfield_office_code' => 'syfers-advies']);
    }
}
