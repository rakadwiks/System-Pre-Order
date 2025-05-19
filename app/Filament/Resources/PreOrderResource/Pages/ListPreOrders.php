<?php

namespace App\Filament\Resources\PreOrderResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\Ticket;
use App\Models\statusOrder;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\PreOrderResource;
use App\Filament\Resources\InfoPreOrderResource\Widgets\PreOrderOverview;


class ListPreOrders extends ListRecords
{
    protected static string $resource = PreOrderResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            PreOrderOverview::class,
        ];
    }

    protected function getTableFilters(): array
    {
        return [

            // 1. Filter berdasarkan STATUS
            SelectFilter::make('status_id')
                ->label('Status')
                ->options(statusOrder::all()->pluck('name', 'id'))
                ->searchable(),

            // 2. Filter berdasarkan USER
            SelectFilter::make('user_id')
                ->label('User')
                ->options(User::all()->pluck('name', 'id'))
                ->searchable(),

            // 3. Filter berdasarkan RENTANG TANGGAL CREATED_AT
            Filter::make('created_at')
                ->form([
                    DatePicker::make('from')->label('From'),
                    DatePicker::make('until')->label('Until'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['from'], fn($q) => $q->whereDate('created_at', '>=', $data['from']))
                        ->when($data['until'], fn($q) => $q->whereDate('created_at', '<=', $data['until']));
                }),

            // 4. Filter berdasarkan TICKET
            SelectFilter::make('ticket_id')
                ->label('Ticket')
                ->options(Ticket::all()->pluck('code_ticket', 'id'))
                ->searchable(),

            // 5. Filter berdasarkan keyword pada CODE_PO
            Filter::make('code_po')
                ->form([
                    TextInput::make('value')->label('Search Code PO'),
                ])
                ->query(function ($query, array $data) {
                    return $query->when(
                        $data['value'],
                        fn($q) =>
                        $q->where('code_po', 'like', '%' . $data['value'] . '%')
                    );
                }),

            // 6. Filter status TIDAK SAMA DENGAN 'rejected'
            Filter::make('not_rejected')
                ->label('Not Rejected')
                ->toggle()
                ->query(function ($query, array $data) {
                    if (! $data['isActive']) {
                        return $query;
                    }

                    $rejectedId = statusOrder::where('name', 'rejected')->value('id');
                    return $query->where('status_id', '!=', $rejectedId);
                }),

        ];
    }
}
