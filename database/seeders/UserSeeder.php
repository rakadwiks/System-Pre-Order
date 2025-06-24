<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Team;
use App\Models\Position;
use App\Models\Division;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Division
        $division = Division::create([
            'name_division' => 'IT',
            'slug' => 'it',
        ]);

        // Position
        $position = Position::create([
            'name_position' => 'IT Support and Software Develop',
            'slug' => 'it-support-and-software-develop',
        ]);

        // Division Admin
        $divisionAdmin = Division::create([
            'name_division' => 'Purchasing',
            'slug' => 'purchasing',
        ]);

        // Position Admin
        $positionAdmin = Position::create([
            'name_position' => 'Purchasing and Procurement',
            'slug' => 'purchasing-and-procurement',
        ]);

        // SuperAdmin
        $team = Team::create([
            'name_team' => 'IT',
            'slug' => 'IT',
            'division_id' => $division->id, // Gunakan id jika PK-nya default
            'position_id' => $position->id,
        ]);

        // SuperAdmin
        $teamAdmin = Team::create([
            'name_team' => 'Dklee',
            'slug' => 'Dklee',
            'division_id' => $divisionAdmin->id, // Gunakan id jika PK-nya default
            'position_id' => $positionAdmin->id,
        ]);

        // Buat user yang terhubung ke tim 
        User::create([
            'name' => 'IT',
            'email' => 'itteam@daehan.co.id',
            'team_id' => $team->id, // Hubungkan ke table teams
            'password' => Hash::make('daehan2025'), // Password aman
            'role_id' => 1, // Eloquent akan otomatis menyimpan sebagai JSON (SuperAdmin)
        ]);

        // Buat user yang terhubung ke tim
        User::create([
            'name' => 'erna',
            'email' => 'erna@daehan.co.id',
            'team_id' => $teamAdmin->id, // Hubungkan ke table teams
            'password' => Hash::make('dhgadmin'), // Password aman
            'role_id' => 2, // Eloquent akan otomatis menyimpan sebagai JSON (Admin)
        ]);
    }
}
