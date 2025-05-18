<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\ProductResource;
use App\Models\Product;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    // merubah titel sesuai dengan database
    public function getTitle(): string
    {
        $product = $this->record; // memanggil code product menggunakan record
        $code = $product->code_product ?? 'Products';
        return "Products - {$code}";
    }
}
