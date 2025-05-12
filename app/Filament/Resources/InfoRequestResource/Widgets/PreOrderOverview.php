<?php

namespace App\Filament\Resources\InfoRequestResource\Widgets;

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

    // protected function getStats(): array
    // {
    //     return [
    //         Stat::make('Tickets', $this->getData()['request'])
    //             ->description('Request')
    //             ->descriptionIcon('heroicon-o-inbox', IconPosition::Before)
    //             ->color('warning'),
    //         Stat::make('',$this->getData()['count'])
    //             ->description('Approved')
    //             ->descriptionIcon('heroicon-o-check-circle',  IconPosition::Before)
    //             ->color('success'),
    //         Stat::make('',$this->getData()['count'])
    //             ->description('Rejected')
    //             ->descriptionIcon('heroicon-o-x-circle',  IconPosition::Before)
    //             ->color('danger'),
    //     ];
    // }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Pre Orders', PreOrder::count())
                ->description('Jumlah seluruh pre-order')
                ->color('info')
                ->icon('heroicon-o-clipboard'),

            Stat::make('Pre Orders Pending', Ticket::whereHas('status', fn($q) => $q->where('name', 'requested'))->count())
                ->description('Masih menunggu diproses')
                ->color('warning')
                ->icon('heroicon-o-clock'),

            Stat::make('Total Tiket', Ticket::count())
                ->description('Jumlah tiket terkait pre-order')
                ->color('success')
                ->icon('heroicon-o-ticket'),

            Stat::make('Tiket Selesai', Ticket::whereHas('status', fn($q) => $q->where('name', 'completed'))->count())
                ->description('Tiket dengan status selesai')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
