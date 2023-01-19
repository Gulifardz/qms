<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            'Product A', 
            'Product B', 
            'Product C', 
            'Product D', 
            'Product E', 
            'Product F', 
            'Product G', 
        ];

        foreach ($products as $product) {
            DB::table('products')->insert([
                'name' => $product,
                'created_at' => now(),
            ]);
        }
    }
}
