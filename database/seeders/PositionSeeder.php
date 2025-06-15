<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // insert data baru untuk divisi
        $positions = [
            'Manager',
            'Assistant Manager',
            'Staff',
            'Senior Merchandiser',
            'Junior Merchandiser',
            'Senior Pattern',
        ];

        foreach ($positions as $position) {
            Position::firstOrCreate(['name_position' => $position]); // mengambil field database name_position
        }
    }
}
