<?php

namespace App\Filament\Resources\PreOrderResource\Pages;


use App\Models\PreOrder;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\PreOrderResource;

class ViewPreOrder extends ViewRecord
{
    protected static string $resource = PreOrderResource::class;

    // merubah titel sesuai dengan code_po
    public function getTitle(): string
    {
        $codePo = PreOrder::where('code_po', $this->record->code_po)->value('code_po'); // mengambil dari database

        return 'Orders - ' . ($codePo ?? 'Pre-Orders');
    }
}
