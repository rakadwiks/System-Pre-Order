<?php

namespace App\Filament\Resources\PreOrderResource\Pages;

use App\Filament\Resources\PreOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreOrder extends EditRecord
{
    protected static string $resource = PreOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
