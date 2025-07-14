<?php

namespace App\Filament\Resources\PreOrderResource\Pages;

use App\Mail\PoCreatedMail;
use Illuminate\Support\Facades\Mail;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PreOrderResource;

class CreatePreOrder extends CreateRecord
{
    protected static string $resource = PreOrderResource::class;
    protected function afterCreate(): void
    {
        $preOrder = $this->record; // model PreOrder yang baru dibuat

        if ($preOrder->user?->email) {
            Mail::to($preOrder->user->email)->send(new PoCreatedMail($preOrder));
        }
    }
}
