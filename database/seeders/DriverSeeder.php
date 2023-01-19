<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('drivers')->insert([
            [
                'company_id' => 1,
                'firstname' => 'Stephen',
                'lastname' => 'Curry',
                'license_no' => '123-45-678910',
                'contact_no' => '09999999971',
                'address' => 'Golden State',
                'picture' => 'https://storage.googleapis.com/oaqms/seeder/346743_346743',
            ],
            [
                'company_id' => 1,
                'firstname' => 'Chris',
                'lastname' => 'Bosh',
                'license_no' => '123-45-678911',
                'contact_no' => '09999999972',
                'address' => 'Miami',
                'picture' => 'https://storage.googleapis.com/oaqms/seeder/384815_384815',
            ]
        ]);
    }
}
