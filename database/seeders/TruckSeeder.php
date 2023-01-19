<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TruckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('trucks')->insert([
            [
                'company_id' => 1,
                'brand' => 'Toyota',
                'year_model' => '2022',
                'capacity' => 6,
                'truck_category_id' => 1,
                'orcr' => '2022000001',
                'plate_no' => 'ABC001',
                'created_at' => now()
            ],
            [
                'company_id' => 1,
                'brand' => 'Ford',
                'year_model' => '2023',
                'capacity' => 6,
                'truck_category_id' => 2,
                'orcr' => '2022000002',
                'plate_no' => 'ABC002',
                'created_at' => now()
            ],
        ]);
    }
}
