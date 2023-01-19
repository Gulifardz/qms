<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CheckerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('checkers')->insert([
            [
                'quarry_id' => '1',
                'firstname' => 'Roronoa',
                'lastname' => 'Zoro',
                'email' => 'roronoa@email.com',
                'password' => Hash::make(1),
                'created_at' => now()
            ],
            [
                'quarry_id' => '2',
                'firstname' => 'Monkey',
                'lastname' => 'D Luffy',
                'email' => 'mugiwara@email.com',
                'password' => Hash::make(1),
                'created_at' => now()
            ],
        ]);
    }
}
