<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components\Section;
use App\Filament\Resources\ProductResource;
use Filament\Infolists\Components\TextEntry;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;
}
