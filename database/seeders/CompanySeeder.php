<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            [
                'name' => 'ABC Company',
                'email' => 'abc_company@email.com',
                'address' => 'Quezon City',
                'contact_no' => '09999999991',
                'password' => Hash::make(1),
            ],
            [
                'name' => 'XYZ Company',
                'email' => 'xyz_company@email.com',
                'address' => 'Makati City',
                'contact_no' => '09999999992',
                'password' => Hash::make(1),
            ],
            [
                'name' => 'RXT Company',
                'email' => 'rxt_company@email.com',
                'address' => 'Cebu City',
                'contact_no' => '09999999993',
                'password' => Hash::make(1),
            ]
        ]);
    }
}
