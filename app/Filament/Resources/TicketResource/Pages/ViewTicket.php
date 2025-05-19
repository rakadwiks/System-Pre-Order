<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Models\Ticket;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\View\View;


class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;
    // merubah titel sesuai dengan database
    public function getTitle(): string
    {
        $ticket = $this->record;
        $code = $ticket->code_ticket ?? 'Tickets';
        $userName = $ticket->user?->name ?? 'Unknown';
        return "Tickets - {$code}";
    }
}
