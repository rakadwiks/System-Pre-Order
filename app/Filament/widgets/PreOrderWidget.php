<?php

namespace App\Filament\Widgets;

use App\Filament\Exports\PreOrderExporter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\PreOrder;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class PreOrderWidget extends BaseWidget
{
    protected static ?string $heading = 'List Pre Order';
    protected int | string | array $columnSpan = 3;
    protected static ?int $sort = 4;

    protected function getTableQuery(): Builder
    {
        return PreOrder::query(); // eager load relasi
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('code_po')->label('PO Code'),

            TextColumn::make('product.name_product')
                ->label('Product Name')
                ->sortable()
                ->searchable(),

            TextColumn::make('ticket.user.name')
                ->label('User Name')
                ->sortable()
                ->searchable(),

            TextColumn::make('total')->label('Total'),

            TextColumn::make('status.name')
                ->label('Status Order')
                ->badge()
                ->searchable()
                ->colors([
                    'warning' => 'Requested',
                    'success' => 'Approved',
                    'info' => 'Completed',
                    'danger' => 'Rejected',
                ]),

        ];
    }
}
