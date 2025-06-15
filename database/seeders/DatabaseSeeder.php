<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            UserSeeder::class,
            RegionSeeder::class,
            StatusSeeder::class,
            StatusTicketSeeder::class,
            DivisionSeeder::class,
            PositionSeeder::class,
        ]);
    }
}
