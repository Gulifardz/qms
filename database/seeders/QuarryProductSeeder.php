<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuarryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quarry_products')->insert([
            [
                'quarry_id' => 1,
                'product_id' => 1,
                'price' => 100,
                'ef' => 48,
                'rmf' => 20,
                'created_at' => now(),
            ],
            [
                'quarry_id' => 1,
                'product_id' => 2,
                'price' => 150,
                'ef' => 48,
                'rmf' => 20,
                'created_at' => now(),
            ],
            [
                'quarry_id' => 1,
                'product_id' => 3,
                'price' => 200,
                'ef' => 48,
                'rmf' => 20,
                'created_at' => now(),
            ],
            [
                'quarry_id' => 1,
                'product_id' => 4,
                'price' => 250,
                'ef' => 48,
                'rmf' => 20,
                'created_at' => now(),
            ],
            [
                'quarry_id' => 1,
                'product_id' => 5,
                'price' => 300,
                'ef' => 48,
                'rmf' => 20,
                'created_at' => now(),
            ],
            [
                'quarry_id' => 1,
                'product_id' => 6,
                'price' => 350,
                'ef' => 48,
                'rmf' => 20,
                'created_at' => now(),
            ],
            [
                'quarry_id' => 1,
                'product_id' => 7,
                'price' => 400,
                'ef' => 48,
                'rmf' => 20,
                'created_at' => now(),
            ],
            [
                'quarry_id' => 2,
                'product_id' => 4,
                'price' => 350,
                'ef' => 48,
                'rmf' => 20,
                'created_at' => now(),
            ],
            [
                'quarry_id' => 2,
                'product_id' => 5,
                'price' => 400,
                'ef' => 48,
                'rmf' => 20,
                'created_at' => now(),
            ],
            [
                'quarry_id' => 2,
                'product_id' => 6,
                'price' => 450,
                'ef' => 48,
                'rmf' => 20,
                'created_at' => now(),
            ],
            [
                'quarry_id' => 2,
                'product_id' => 7,
                'price' => 500,
                'ef' => 48,
                'rmf' => 20,
                'created_at' => now(),
            ]
        ]);
    }
}
