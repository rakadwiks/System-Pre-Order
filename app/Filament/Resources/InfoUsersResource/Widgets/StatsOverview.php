<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
class StatsOverview extends BaseWidget
{
    // Count the number of users
    protected function getData(): array
    {
        return [
            'count' => User::count(), // Retrieve the total number of users from the ‘users’ table
        ];
    }

    protected function getStats(): array
    {
        return [
            Stat::make('', $this->getData()['count'])
                ->description('Request')
                ->descriptionIcon('heroicon-o-inbox', IconPosition::Before)
                ->color('warning'),
            Stat::make('', '21%')
                ->description('Approved')
                ->descriptionIcon('heroicon-o-check-circle',  IconPosition::Before)
                ->color('success'),
            Stat::make('', '3:12')
                ->description('Rejected')
                ->descriptionIcon('heroicon-o-x-circle',  IconPosition::Before)
                ->color('danger'),
        ];
    }
}