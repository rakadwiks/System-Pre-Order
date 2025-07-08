<?php

namespace App\Filament\Exports;

use App\Models\PreOrder;
use App\Models\Ticket;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PreOrderExporter extends Exporter
{
    protected static ?string $model = Ticket::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('code_ticket'),
            ExportColumn::make('photos'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your pre order export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
