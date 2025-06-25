<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use App\Models\Product;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProductResource;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\CreateAction::make('Add Stock') // membuat header action Add Stock
                ->label('Add Stock')
                ->icon('heroicon-o-plus')
                ->visible(fn() => Product::exists())
                ->form([
                    Grid::make()
                        ->columns(6) // jumlah kolom grid tetap HARUS static (int), bukan closure
                        ->schema([
                            Select::make('product_id')
                                ->label('Product')
                                ->options(Product::all()->pluck('name_product', 'id'))
                                ->searchable()
                                ->required()
                                ->columnSpan(fn(string $context) => $context === 'create' ? 2 : 3),

                            TextInput::make('in_stock')
                                ->label('Stock Quantity')
                                ->numeric()
                                ->required()
                                ->columnSpan(fn(string $context) => $context === 'create' ? 2 : 3)
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    $productId = $get('product_id');
                                    if ($productId) {
                                        $product = Product::find($productId);
                                        if ($product) {
                                            $name = $product->name_product;
                                            Notification::make()
                                                ->title('Success')
                                                ->body("Product {$name} added successfully.")
                                                ->success()
                                                ->send();
                                        }
                                    }
                                }),
                            TextInput::make('price')
                                ->label('Harga Satuan')
                                ->disabled()
                                ->numeric()
                                ->live()
                                ->visible(fn($get) => filled($get('product_id'))),
                        ]),
                ])
                ->action(function (array $data) {
                    $product = Product::find($data['product_id']);

                    if ($product) {
                        $inStock = intval($data['in_stock']);
                        $price = intval($product->price);

                        // Tambahkan nilai ke in_stock
                        $product->in_stock += $inStock;

                        // Hitung ulang final_stock terlebih dahulu
                        $product->final_stock = $product->stock + $product->in_stock - intval($product->out_stock);

                        // Baru kemudian hitung total_price berdasarkan final_stock
                        $product->total_price = $price * $product->final_stock;

                        $product->save();
                    }
                })


                ->successNotificationTitle('Stock successfully added!')
                ->color('success'),

        ];
    }
}
