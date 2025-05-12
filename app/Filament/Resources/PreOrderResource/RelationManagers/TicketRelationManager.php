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
            Tables\Columns\TextColumn::make('description')->label('Description'),
            Tables\Columns\TextColumn::make('status.name')->label('Status')
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
