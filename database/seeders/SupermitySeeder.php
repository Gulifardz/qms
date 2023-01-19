<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SupermitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('supermities')->insert([
            [
                'quarry_id' => '1',
                'firstname' => 'Scooper',
                'lastname' => 'Gaban',
                'email' => 'gaban@email.com',
                'password' => Hash::make(1),
                'created_at' => now()
            ],
            [
                'quarry_id' => '2',
                'firstname' => 'Silvers',
                'lastname' => 'rayleigh',
                'email' => 'rayleigh@email.com',
                'password' => Hash::make(1),
                'created_at' => now()
            ],
        ]);
    }
}
