<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TruckDriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('truck_drivers')->insert([
            [
                'company_id' => 1,
                'truck_id' => 1,
                'driver_id' => 1,
                'created_at' => now()
            ],
            [
                'company_id' => 1,
                'truck_id' => 2,
                'driver_id' => 2,
                'created_at' => now()
            ],
        ]);
    }
}
