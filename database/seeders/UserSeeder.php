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
            'name_division' => 'IT Department',
            'slug' => 'it-departement',
        ]);
        // Position
        $position = Position::create([
            'name_position' => 'IT Support & Software Develop',
            'slug' => 'it-support-&-software-develop',
        ]);
        // Buat tim secara manual
        $team = Team::create([
            'name_team' => 'IT',
            'slug' => 'IT',
            'division_id' => $division->id, // Gunakan id jika PK-nya default
            'position_id' => $position->id,
        ]);

        // Buat user yang terhubung ke tim tersebut
        User::create([
            'name' => 'IT',
            'email' => 'itteam@daehan.co.id',
            'team_id' => $team->id, // Hubungkan ke table teams
            'password' => Hash::make('daehan2025'), // Password aman
            'role' => ['superadmin'] // Eloquent akan otomatis menyimpan sebagai JSON
        ]);
    }
}
