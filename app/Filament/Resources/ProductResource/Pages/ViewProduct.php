<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components\Section;
use App\Filament\Resources\ProductResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry as InfolistTextEntry;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;
    
   
    public function infolists(): array
    {
        return [
            Infolist::make()
                ->schema([
                    Section::make('Product Info')
                        ->schema([
                            Placeholder::make('supplier.name_supplier')
                        ->label('Supplier Name')
                        ->content(fn ($record) => $record->supplier->name_supplier ?? '-'),
                            TextEntry::make('name')->label('Product Name'),
                            TextEntry::make('price')->label('Price'),
                            TextEntry::make('description')->label('Description'),
                        ])
                        ->columns(2),

                        Section::make('Supplier Info')
                        ->schema([
                            // Gunakan $this->record->supplier untuk mengakses relasi supplier
                            Placeholder::make('supplier_name')
                            ->label('Supplier Name')
                            ->content(fn ($record) => $record->supplier->name_supplier ?? '-'),
                            Placeholder::make('phone')
                            ->label('Supplier Name')
                            ->content(fn ($record) => $record->supplier->phone ?? '-'),
                        ])
                        ->columns(2),
                ]),
        ];
    }
}

