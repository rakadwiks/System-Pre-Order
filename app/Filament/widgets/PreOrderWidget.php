<?php

namespace App\Filament\Widgets;

use Illuminate\Database\Eloquent\Builder;
use App\Models\PreOrder;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class PreOrderWidget extends BaseWidget
{
    protected static ?string $heading = 'List Pre Order';
    protected int | string | array $columnSpan = '1/2';
    protected static ?int $sort = 2;

    protected function getTitle(): string
    {
        return 'List Pre Order';
    }

    protected function getTableQuery(): Builder
    {
        return PreOrder::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('code_po')->label('PO Code'),
            TextColumn::make('id_product')->label('P'),
            TextColumn::make('id_users')->label('User ID'),
            TextColumn::make('id_supplier')->label('Supplier ID'),
            TextColumn::make('total')->label('Total'),
            TextColumn::make('status')
                ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'submitted' => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger',
                    'completed' => 'info',
                    'cancelled' => 'danger',
                }),
        ];
    }
}
