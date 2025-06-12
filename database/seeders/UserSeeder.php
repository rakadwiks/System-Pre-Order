<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Team;
use App\Models\Position;
use App\Models\Division;
use App\Models\Roles;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Division
        $division = Division::create([
            'name_division' => 'IT Department',
            'slug' => 'it-departement',
        ]);

        $tempDivision = Division::create([
            'name_division' => 'Temporary Department',
            'slug' => 'temporary-departement',
        ]);


        // Position
        $position = Position::create([
            'name_position' => 'IT Support & Software Develop',
            'slug' => 'it-support-&-software-develop',
        ]);

        $managerPosition = Position::create([
            'name_position' => 'Manager',
            'slug' => 'manager',
        ]);

        // Buat tim secara manual
        $team = Team::create([
            'name_team' => 'IT',
            'slug' => 'IT',
            'division_id' => $division->id, // Gunakan id jika PK-nya default
            'position_id' => $position->id,
        ]);

        $tempTeam = Team::create([
            'name_team' => 'Temporary Team',
            'slug' => 'temporary-team',
            'division_id' => $tempDivision->id, // Gunakan id jika PK-nya default
            'position_id' => $managerPosition->id,
        ]);

        // Buat user yang terhubung ke tim tersebut
        User::create([
            'name' => 'IT',
            'email' => 'itteam@daehan.co.id',
            'team_id' => $team->id, // Hubungkan ke table teams
            'password' => Hash::make('daehan2025'), // Password aman
            'role_id' => 1, // Eloquent akan otomatis menyimpan sebagai JSON
        ]);

        // User::create([
        //     'name' => 'Vikri',
        //     'email' => 'vikri@daehan.co.id',
        //     'team_id' => $tempTeam->id, // Hubungkan ke table teams
        //     'password' => Hash::make('daehan2025'), // Password aman
        //     'role' => ['admin'] // Eloquent akan otomatis menyimpan sebagai JSON
        // ]);
    }
}
