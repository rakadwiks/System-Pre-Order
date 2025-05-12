<?php

namespace App\Filament\Widgets;

use App\Models\PreOrder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use Filament\Support\Enums\IconPosition;

class StatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;
    protected function getData(): array
    {
        return [
            'po_count' => PreOrder::count(), // Retrieve the total number of users from the ‘users’ table
            'submitted_count' => PreOrder::where('status', 'submitted')->count(),
            'approved_count' => PreOrder::where('status', 'approved')->count(),
            'rejected_count' => PreOrder::where('status', 'rejected')->count(),
            'completed_count' => PreOrder::where('status', 'completed')->count(),
            'cancelled_count' => PreOrder::where('status', 'cancelled')->count(),
        ];
    }

    protected function getStats(): array
    {
        $data = $this->getData();

        return [

            Stat::make('', $data['submitted_count'])
                ->description('Total Submitted ')
                ->descriptionIcon('heroicon-o-clock', IconPosition::Before)
                ->color('warning'),

            Stat::make('', $data['approved_count'])
                ->description('Total Approved ')
                ->descriptionIcon('heroicon-o-check-circle', IconPosition::Before)
                ->color('info'),

            Stat::make('', $data['rejected_count'])
                ->description('Total Rejected ')
                ->descriptionIcon('heroicon-o-x-circle', IconPosition::Before)
                ->color('danger'),

            Stat::make('', $data['po_count'])
                ->description('Total Pre Orders')
                ->descriptionIcon('heroicon-o-inbox', IconPosition::Before)
                ->color('secondary'),

            Stat::make('', $data['completed_count'])
                ->description('Total Completed ')
                ->descriptionIcon('heroicon-o-check-badge', IconPosition::Before)
                ->color('success'),

            Stat::make('', $data['cancelled_count'])
                ->description('Total Cancelled ')
                ->descriptionIcon('heroicon-o-trash', IconPosition::Before)
                ->color('danger'),
        ];
    }
}
