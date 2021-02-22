<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Administration;

class AdministrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administration = Administration::create([
            'name' => 'Testadministratie vraagposten app', 
            'code' => '9999', 
            'call_posts_code' => '2999',
            'debtors_code' => '1300', 
            'creditors_code' => '1600', 
            'account_id' => 1, 
            'relation_manager_id' => 1, 
            'contact_first_name' => 'Sandra', 
            'contact_last_name' => 'Berkens', 
            'contact_email' => 'sandraberkens@platform11.nl',
        ]);

        $administration->setStatus('new', 'Deze administratie bevat nog geen verstuurde overzichten en heeft daarom de status nieuw');

        $administration = Administration::create([
            'name' => 'Een andere administratie', 
            'code' => '9998',
            'call_posts_code' => '2999',
            'debtors_code' => '1300', 
            'creditors_code' => '1600', 
            'account_id' => 1, 
            'relation_manager_id' => 2, 
            'contact_first_name' => 'Pieter', 
            'contact_last_name' => 'Post', 
            'contact_email' => 'pieterpost@platform11.nl',
        ]);

        $administration->setStatus('new', 'Deze administratie bevat nog geen verstuurde overzichten en heeft daarom de status nieuw');

        $administration = Administration::create([
            'name' => 'Adminstratie 3', 
            'code' => '9999', 
            'call_posts_code' => '2999',
            'debtors_code' => '1300', 
            'creditors_code' => '1600', 
            'account_id' => 2,
            'relation_manager_id' => 3, 
            'contact_first_name' => 'Job', 
            'contact_last_name' => 'Hupenbos', 
            'contact_email' => 'jobhupenbos@platform11.nl',
        ]);

        $administration->setStatus('new', 'Deze administratie bevat nog geen verstuurde overzichten en heeft daarom de status nieuw');

        $administration = Administration::create([
            'name' => 'Administratie 4', 
            'code' => '9999',
            'call_posts_code' => '2999',
            'debtors_code' => '1300', 
            'creditors_code' => '1600', 
            'account_id' => 2, 
            'relation_manager_id' => 4, 
            'contact_first_name' => 'Lizzy', 
            'contact_last_name' => 'van der Meer', 
            'contact_email' => 'lizzyvdmeer@platform11.nl',
        ]);

        $administration->setStatus('new', 'Deze administratie bevat nog geen verstuurde overzichten en heeft daarom de status nieuw');

    }
}
