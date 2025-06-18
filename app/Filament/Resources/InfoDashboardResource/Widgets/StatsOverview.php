<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\User;
use App\Models\PreOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected static ?string $model = User::class;
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

        $data['money_spent'] = PreOrder::where('status_id', $statusMap['completed'])
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
                ->chart($data['requested_chart']),

            Stat::make('Total Rejected', $data['rejected_count'])
                ->description('Rejected in 7 days')
                ->descriptionIcon('heroicon-o-x-circle', IconPosition::Before)
                ->color('danger')
                ->chart($data['rejected_chart']),

            Stat::make('Total Completed', $data['completed_count'])
                ->description('Completed PO in 7 days ')
                ->descriptionIcon('heroicon-o-check-badge', IconPosition::Before)
                ->color('success')
                ->chart($data['completed_chart']),

            Stat::make('Total Orders', $data['po_count'])
                ->description(' ')
                ->descriptionIcon('heroicon-o-inbox', IconPosition::Before)
                ->color('secondary'),

            Stat::make('total Expenditure', 'Rp ' . number_format($data['money_spent'], 2, ',', '.'))
                ->description('Pengeluaran keuangan dari PO completed')
                ->descriptionIcon('heroicon-o-banknotes', IconPosition::Before)
                ->color('danger'),
        ];
    }

    // Middleware untuk Hak Akses Superadmin, Admin, User
    public static function canViewAny(): bool
    {
        return Auth::user()?->hasRole(['SuperAdmin', 'Admin']);
    }
    public static function canView(): bool
    {
        return Auth::user()?->hasRole(['SuperAdmin', 'Admin']);
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->hasRole(['SuperAdmin', 'Admin']);
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()?->hasRole(['SuperAdmin', 'Admin']);
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()?->hasRole(['superadmin', 'Admin']);
    }
}
