<?php

namespace Database\Seeders;

use App\Models\statusOrder;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['Requested', 'Approved', 'Completed', 'Rejected'];

        foreach ($statuses as $status) {
            statusOrder::firstOrCreate(['name' => $status]);
        }
    }
}
