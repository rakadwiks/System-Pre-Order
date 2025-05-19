<?php

namespace App\Filament\Resources\TicketResource\Pages;

use Filament\Actions;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TicketResource;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        // Ambil semua data jika admin
        if (Auth::user()?->hasRole(['superadmin', 'admin'])) {
            return Ticket::query();
        }

        // Jika bukan admin, hanya tampilkan data berdasarkan user login
        return Ticket::query()->where('user_id', Auth::id());
    }
}
