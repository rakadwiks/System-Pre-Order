<?php

namespace App\Filament\Widgets;

use App\Models\PreOrder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;
    protected ?string $heading = 'Stats Overview';

    protected function getData(): array
    {
        $startDate = Carbon::now()->subDays(6); // 7 hari termasuk hari ini

        $statuses = ['request', 'approved', 'rejected', 'completed', 'cancelled'];

        $data = [
            'po_count' => PreOrder::count(),
            'request_count' => PreOrder::where('status', 'request')->count(),
            'approved_count' => PreOrder::where('status', 'approved')->count(),
            'rejected_count' => PreOrder::where('status', 'rejected')->count(),
            'completed_count' => PreOrder::where('status', 'completed')->count(),
            'cancelled_count' => PreOrder::where('status', 'cancelled')->count(),
        ];

        foreach ($statuses as $status) {
            $dailyData = PreOrder::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
                ->where('status', $status)
                ->where('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('count', 'date')
                ->toArray();

            // Buat array 7 hari dengan nilai default 0
            $counts = [];
            for ($i = 0; $i < 7; $i++) {
                $date = $startDate->copy()->addDays($i)->format('Y-m-d');
                $counts[] = $dailyData[$date] ?? 0;
            }

            $data["{$status}_chart"] = $counts;
        }

        // Total pengeluaran produk (jumlah total unit dari pre order yang completed)
        $data['product_spent'] = PreOrder::where('status', 'completed')->sum('total');

        // Total pengeluaran keuangan (jumlah uang berdasarkan total * harga produk)
        $data['money_spent'] = PreOrder::where('status', 'completed')
            ->join('products', 'pre_orders.product_id', '=', 'products.id')
            ->selectRaw('SUM(pre_orders.total * products.price) as total_spent')
            ->value('total_spent') ?? 0;

        return $data;
    }

    protected function getStats(): array
    {
        $data = $this->getData();

        return [
            Stat::make('Total Request', $data['request_count'])
                ->description('Request in 7 days ')
                ->descriptionIcon('heroicon-o-clock', IconPosition::Before)
                ->color('warning')
                ->chart($data['request_chart']),

            Stat::make('Total Approved', $data['approved_count'])
                ->description('Approved in 7 days ')
                ->descriptionIcon('heroicon-o-check-circle', IconPosition::Before)
                ->color('info')
                ->chart($data['approved_chart']),

            Stat::make('Total Rejected', $data['rejected_count'])
                ->description('Rejected in 7 days')
                ->descriptionIcon('heroicon-o-x-circle', IconPosition::Before)
                ->color('danger')
                ->chart($data['rejected_chart']),

            Stat::make('Total Pre Orders', $data['po_count'])
                ->description(' ')
                ->descriptionIcon('heroicon-o-inbox', IconPosition::Before)
                ->color('secondary'),

            Stat::make('Total Completed', $data['completed_count'])
                ->description('Completed PO in 7 days ')
                ->descriptionIcon('heroicon-o-check-badge', IconPosition::Before)
                ->color('success')
                ->chart($data['completed_chart']),

            Stat::make('Total Cancelled', $data['cancelled_count'])
                ->description('Cancelled PO in 7 days')
                ->descriptionIcon('heroicon-o-trash', IconPosition::Before)
                ->color('danger')
                ->chart($data['cancelled_chart']),

            Stat::make('Total Unit Keluar', number_format($data['product_spent']))
                ->description('Total produk yang keluar (unit)')
                ->descriptionIcon('heroicon-o-arrow-trending-down', IconPosition::Before)
                ->color('info'),

            Stat::make('Total Pengeluaran', 'Rp ' . number_format($data['money_spent'], 2, ',', '.'))
                ->description('Pengeluaran keuangan dari PO completed')
                ->descriptionIcon('heroicon-o-banknotes', IconPosition::Before)
                ->color('danger'),
        ];
    }
}
