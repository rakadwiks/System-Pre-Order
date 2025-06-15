<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // insert data baru untuk divisi
        $divisions = [
            'GAP',
            'K-Mart',
            'Purchasing',
            'Accounting',
            'Laboratory Washing',
            'Old Navy',
            'Human Reasource Development',
            'Price Ticket',
            'Export',
            'Import',
            'Pattern',
            'Cutting',
            'Production',
            'Plainer',
            'Quality Assurance',
            'Research and Development',
            'Prudct Design',
            'Enterprise Resource Planning',
            'Computer-Aided Design'
        ];

        foreach ($divisions as $division) {
            Division::firstOrCreate(['name_division' => $division]); // mengambil field database name_division
        }
    }
}
