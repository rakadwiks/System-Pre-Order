<?php

namespace App\Filament\Widgets;

use App\Models\PreOrder;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\Facades\DB;
use App\Models\statusOrder;
use App\Models\Supplier;

class StatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;
    protected ?string $heading = 'Stats Overview';

    protected function getData(): array
    {
        $startDate = Carbon::now()->subDays(6); // 7 hari termasuk hari ini

        $statusMap = [
            'requested' => 1,
            'approved' => 2,
            'completed' => 3,
            'rejected' => 4,
        ];

        $data = [
            'po_count' => PreOrder::count(),
            'request_count' => PreOrder::where('status_id', $statusMap['requested'])->count(),
            'approved_count' => PreOrder::where('status_id', $statusMap['approved'])->count(),
            'rejected_count' => PreOrder::where('status_id', $statusMap['rejected'])->count(),
            'completed_count' => PreOrder::where('status_id', $statusMap['completed'])->count(),
        ];

        foreach ($statusMap as $key => $id) {
            $dailyData = PreOrder::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
                ->where('status_id', $id)
                ->where('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('count', 'date')
                ->toArray();

            $counts = [];
            for ($i = 0; $i < 7; $i++) {
                $date = $startDate->copy()->addDays($i)->format('Y-m-d');
                $counts[] = $dailyData[$date] ?? 0;
            }

            $data["{$key}_chart"] = $counts;
        }

        $data['product_spent'] = PreOrder::where('status_id', $statusMap['completed'])->sum('total');

        $data['money_spent'] = Product::sum('total_price') ?? 0;

        return $data;
    }

    protected function getStats(): array
    {
        $data = $this->getData();

        return [

            Stat::make('Requested', PreOrder::whereHas('status', fn($q) => $q->where('name', 'requested'))->count())
                ->description('Waiting for confirmation ')
                ->color('warning')
                ->icon('heroicon-o-clock')
                ->chart($data['requested_chart']),
            Stat::make('Approved', PreOrder::whereHas('status', fn($q) => $q->where('name', 'approved'))->count())
                ->description('It has been approved')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->chart($data['approved_chart']),
            Stat::make('Completed', PreOrder::whereHas('status', fn($q) => $q->where('name', 'completed'))->count())
                ->description('Order is completed')
                ->color('info')
                ->icon('heroicon-o-check-badge')
                ->chart($data['completed_chart']),
            Stat::make('Rejected', PreOrder::whereHas('status', fn($q) => $q->where('name', 'rejected'))->count())
                ->description('Not approved')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->chart($data['rejected_chart']),
        ];
    }
}
