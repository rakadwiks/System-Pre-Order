<?php

namespace App\Filament\Resources\ProductChartResource\Widgets;

use App\Models\Product;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;

class ProductChart extends ChartWidget
{
    protected static ?string $heading = 'Total Expenditure';
    protected static string $color = 'info';
    protected static ?string $icon = 'heroicon-o-chart-bar'; // <- Tambah ikon di sini
    protected static ?string $iconColor = 'info';
    protected static ?string $iconBackgroundColor = 'info';
    protected static ?string $label = 'Monthly users chart';
    protected static ?string $badge = 'new';
    protected static ?string $badgeColor = 'success';
    protected static ?string $badgeIcon = 'heroicon-o-check-circle';
    protected static ?string $badgeIconPosition = 'after';
    protected static ?string $badgeSize = 'xs';
    protected int | string | array $columnSpan = 'full'; // ukuran kolomnya
    protected static ?int $sort = 2;

    public ?string $filter = 'today';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter ?? 'today';

        $query = Product::query();

        $labels = [];
        $data = [];

        if ($filter === 'today') {
            // Per jam (24 jam)
            $query->whereDate('created_at', Carbon::today());
            $dataByHour = $query
                ->selectRaw('HOUR(created_at) as hour, SUM(total_price) as total')
                ->groupBy('hour')
                ->pluck('total', 'hour');

            $labels = range(0, 23);
            $data = collect($labels)->map(fn($h) => $dataByHour[$h] ?? 0)->toArray();
        }

        if ($filter === 'week') {
            // 7 hari terakhir
            $start = now()->subDays(6)->startOfDay();
            $query->where('created_at', '>=', $start);

            $dataByDay = $query
                ->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date');

            $labels = collect(range(0, 6))->map(fn($i) => now()->subDays(6 - $i)->format('Y-m-d'))->toArray();
            $data = collect($labels)->map(fn($date) => $dataByDay[$date] ?? 0)->toArray();
        }

        if ($filter === 'month') {
            // Per hari dalam bulan ini
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            $daysInMonth = now()->daysInMonth;

            $dataByDay = $query
                ->selectRaw('DAY(created_at) as day, SUM(total_price) as total')
                ->groupBy('day')
                ->pluck('total', 'day');

            $labels = range(1, $daysInMonth);
            $data = collect($labels)->map(fn($d) => $dataByDay[$d] ?? 0)->toArray();
        }

        if ($filter === 'year') {
            // Per bulan dalam tahun ini
            $query->whereYear('created_at', now()->year);

            $dataByMonth = $query
                ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
                ->groupBy('month')
                ->pluck('total', 'month');

            $labels = [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            ];

            $data = collect(range(1, 12))->map(fn($m) => $dataByMonth[$m] ?? 0)->toArray();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Expenditure',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }


    protected function getType(): string
    {
        return 'line';
    }
}
