<?php

namespace App\Filament\Resources\InfoPreOrderResource\Widgets;

use App\Models\User;
use App\Models\Ticket;
use App\Models\PreOrder;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class PreOrderOverview extends BaseWidget
{
    // Count the number of users
    protected function getData(): array
    {
        return [
            'count' => User::count(),
            'request' => Ticket::count(),
        ];
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Requested', PreOrder::whereHas('status', fn($q) => $q->where('name', 'requested'))->count())
                ->description('Waiting for confirmation ')
                ->color('warning')
                ->icon('heroicon-o-clock'),
            Stat::make('Approved', PreOrder::whereHas('status', fn($q) => $q->where('name', 'approved'))->count())
                ->description('It has been approved')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
            Stat::make('Completed', PreOrder::whereHas('status', fn($q) => $q->where('name', 'completed'))->count())
                ->description('Order is completed')
                ->color('info')
                ->icon('heroicon-o-check-badge'),
            Stat::make('Rejected', PreOrder::whereHas('status', fn($q) => $q->where('name', 'rejected'))->count())
                ->description('Not approved')
                ->color('danger')
                ->icon('heroicon-o-x-circle'),
        ];
    }
    protected function getColumns(): int
    {
        return 4; // mengatur ukuran card info status
    }
}
