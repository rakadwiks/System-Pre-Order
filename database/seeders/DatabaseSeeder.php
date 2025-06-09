<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        ]);
    }
}
