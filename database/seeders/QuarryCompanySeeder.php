<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuarryCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quarry_companies')->insert([
            [
                'quarry_id' => 1,
                'company_id' => 1,
                'created_at' => now(),
            ],
            [
                'quarry_id' => 1,
                'company_id' => 2,
                'created_at' => now(),
            ],
            [
                'quarry_id' => 2,
                'company_id' => 3,
                'created_at' => now(),
            ]
        ]);
    }
}
