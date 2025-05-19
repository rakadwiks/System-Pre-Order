<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as DefaultDashboard;

class Dashboard extends DefaultDashboard
{
    public function getColumns(): int | string | array
    {
        return 4;
    }
}
