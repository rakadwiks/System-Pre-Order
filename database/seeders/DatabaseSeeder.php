<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       // Create Superadmin
       User::create([
        'name' => 'IT',
        'email' => 'itteam@daehan.co.id',
        'password' => Hash::make('daehan2025'), // Menggunakan password yang aman
        'role' => 'superadmin', // Role superadmin
    ]);

    // Craete admin
    User::create([
        'name' => 'Admin',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('admin'),
        'role' => 'admin',
    ]);

    $this->call(DatabaseSeeder::class);
    }
}
