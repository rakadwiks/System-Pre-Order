<?php

namespace App\Filament\Resources\PreOrderResource\Pages;

use Tabs\Tab;
use Filament\Actions;
use Filament\Infolists\Components\Tabs;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Components\Section;
use App\Filament\Resources\PreOrderResource;
use App\Filament\Widgets\PreOrderInfoWidget;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\InfoRequestResource\Widgets\PreOrderOverview;

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
}
