<?php

namespace App\Filament\Resources\PreOrderResource\Pages;


use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\PreOrderResource;

class ViewPreOrder extends ViewRecord
{
    protected static string $resource = PreOrderResource::class;
    public function getTitle(): string
    {
        return 'Tickets - ' . ($this->record->ticket?->code_ticket);
    }

}
