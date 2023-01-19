<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TruckCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('truck_categories')->insert([
            [
                'name' => 'Small',
                'otf' => 100
            ],
            [
                'name' => 'Medium',
                'otf' => 150
            ],
            [
                'name' => 'Large',
                'otf' => 250
            ]
        ]);
    }
}
