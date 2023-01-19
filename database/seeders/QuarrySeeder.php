<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class QuarrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quarries')->insert([
            [
                'name' => 'Sandbox Quarry',
                'email' => 'sandbox_quarry@email.com',
                'address' => 'Lucena City',
                'contact_no' => '09999999964',
                'lgu' => 'N/A',
                'host_barangay' => 'N/A',
                'quarry_area_td' => 'N/A',
                'ie_route' => json_encode(['Agdangan', 'Unisan']),
                'provincial_permit' => json_encode(['A', 'B']),
                'regional_permit' => json_encode(['C', 'D']),
                'password' => Hash::make(1),
            ],
            [
                'name' => 'Happy Quarry',
                'email' => 'happy_quarry@email.com',
                'address' => 'San Francisco Quezon',
                'contact_no' => '09999999965',
                'lgu' => 'N/A',
                'host_barangay' => 'N/A',
                'quarry_area_td' => 'N/A',
                'ie_route' => json_encode(['Mulanay', 'San Andres']),
                'provincial_permit' => json_encode(['A', 'B']),
                'regional_permit' => json_encode(['C', 'D']),
                'password' => Hash::make(1),
            ]
        ]);
    }
}
