<?php

namespace Database\Seeders;

use App\Models\StatusTicket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $statuses = ['Requested', 'Approved', 'Rejected'];

        foreach ($statuses as $status) {
            StatusTicket::firstOrCreate(['name' => $status]);
        }
    }
}
