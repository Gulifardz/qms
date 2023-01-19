<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1)->create();

        $this->call([
            ProductSeeder::class,
            CompanySeeder::class,
            TruckCategorySeeder::class,
            QuarrySeeder::class,
            QuarryCompanySeeder::class,
            QuarryProductSeeder::class,
            CheckerSeeder::class,
            SupermitySeeder::class,
            DriverSeeder::class,
            TruckSeeder::class,
            TruckDriverSeeder::class
        ]);
    }
}
