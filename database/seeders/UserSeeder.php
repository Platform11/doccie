<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'Esther', 
            'last_name' => 'Roelofs', 
            'email' => 'esther@oneaccountants.nl', 
            'email_verified_at' => now(), 
            'password' => Hash::make('test1234'),
            'twinfield_username' => 'nretel', 
            'twinfield_password' => '8.Jo9ihLKpXFiUdLy',
            'account_id' => 1,
        ]);

        $user->setStatus('active');

        $account = Account::find(2);
        $account->admin_id = $user->id;
        $account->save();

        $user = User::create([
            'first_name' => 'Steven', 
            'last_name' => 'van Duijvenbode', 
            'email' => 'steven@oneaccountants.nl', 
            'email_verified_at' => now(), 
            'password' => Hash::make('test1234'),
            'twinfield_username' => 'steven', 
            'twinfield_password' => 'teads',
            'account_id' => 1,
        ]);

        $user->setStatus('active');

        $user = User::create([
            'first_name' => 'Johan', 
            'last_name' => 'Vlietland', 
            'email' => 'johan@test.nl', 
            'email_verified_at' => now(), 
            'password' => Hash::make('test1234'),
            'twinfield_username' => 'jv', 
            'twinfield_password' => '1234',
            'account_id' => 2,
        ]);

        $user->setStatus('active');

        $account = Account::find(2);
        $account->admin_id = $user->id;
        $account->save();

        $user = User::create([
            'first_name' => 'Suzanne', 
            'last_name' => 'Gauweling', 
            'email' => 'suzanne@test.nl', 
            'email_verified_at' => now(), 
            'password' => Hash::make('test1234'),
            'twinfield_username' => 'sg', 
            'twinfield_password' => Crypt::encryptString('1234'),
            'account_id' => 2,
        ]);

        $user->setStatus('active');
    }
}
