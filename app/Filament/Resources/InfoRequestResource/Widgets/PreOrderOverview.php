<?php

namespace App\Filament\Resources\InfoRequestResource\Widgets;

use App\Models\User;
use App\Models\Ticket;
use App\Models\PreOrder;
use App\Models\Status;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

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
            Stat::make('Pre Orders', PreOrder::count())
                ->description('Total all Pre-Orders')
                ->color('gray')
                ->icon('heroicon-o-clipboard'),
            Stat::make('Requested', PreOrder::whereHas('status', fn($q) => $q->where('name', 'requested'))->count())
                ->description('Waiting for confirmation ')
                ->color('warning')
                ->icon('heroicon-o-check-circle'),
            Stat::make('Approved', PreOrder::whereHas('status', fn($q) => $q->where('name', 'approved'))->count())
                ->description('It has been approved')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
            Stat::make('Rejected', PreOrder::whereHas('status', fn($q) => $q->where('name', 'rejected'))->count())
                ->description('Not approved')
                ->color('danger')
                ->icon('heroicon-o-check-circle'),
            Stat::make('Completed', PreOrder::whereHas('status', fn($q) => $q->where('name', 'completed'))->count())
                ->description('Order is complete')
                ->color('info')
                ->icon('heroicon-o-check-circle'),
        ];
    }
    protected function getColumns(): int
    {
        return 4; // mengatur ukuran card info status
    }
}
