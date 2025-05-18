<?php

namespace App\Filament\Resources\PreOrderResource\RelationManagers;


use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;

class TicketRelationManager extends RelationManager
{
    protected static string $relationship = 'ticket';
    protected static ?string $title = "Tickets";

    // Tabel untuk menampilkan daftar tickets yang terkait
    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('code_ticket')->label('Code Ticket'),
            Tables\Columns\TextColumn::make('user.name')->label('User Complaint'),
            Tables\Columns\ImageColumn::make('photos')
                ->label('Evidence of error')
                ->getStateUsing(function ($record) {
                    // Ubah JSON string menjadi array
                    $photos = is_string($record->photos)
                        ? json_decode($record->photos, true)
                        : $record->photos;

                    // Ambil value pertama dari associative array
                    if (is_array($photos) && count($photos) > 0) {
                        $firstPath = array_values($photos)[0];
                        return asset('storage/' . $firstPath); // hasil: http://localhost/storage/ticket-photos/xxx.png
                    }

                    return null;
                })
                ->disk('public'),
            Tables\Columns\TextColumn::make('description')->label('Description'),
            Tables\Columns\TextColumn::make('statusOrder.name')->label('Status')
                ->badge()
                ->colors([
                    'warning' => 'Request',
                    'success' => 'Approved',
                    'info' => 'Completed',
                    'danger' => 'Rejected',
                ]),
        ]);
    }
}
