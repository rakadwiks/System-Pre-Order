<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat Superadmin
        User::create([
            'name' => 'IT',
            'email' => 'itteam@daehan.co.id',
            'password' => Hash::make('daehan2025'), // Menggunakan password yang aman
            'role' => 'superadmin', // Role superadmin
        ]);

        // Membuat User Biasa
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'user',
        ]);
        
        $this->call(UserSeeder::class);
        
    }
    
}


