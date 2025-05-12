<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Product;
use App\Models\PreOrder;
use Filament\Forms\Form;
use App\Rules\validateAll;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Validation\ValidationException;
use App\Filament\Resources\PreOrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PreOrderResource\RelationManagers;

class PreOrderResource extends Resource
{
    protected static ?string $model = PreOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form 
            ->schema([
                //mmebuat nomer PO otomatis
                Forms\Components\TextInput::make('code_po')
                ->default(function () {
                    $date = date('Ymd');
                    $randomNumber = mt_rand(1000, 9999); // 4 digit
                    $randomString = strtoupper(Str::random(3)); // 3 huruf kapital
                    return 'PO-' . $date . '-' . $randomNumber . '-' . $randomString;
                })
                ->readOnly()
                ->disabled()
                ->dehydrated(),
                Select::make('product_id')
                    ->label('Product')
                    ->options(Product::all()->pluck('name_product', 'id'))
                    ->reactive()
                    ->required()
                    ->searchable(),               
                // Field Hidden untuk user_id (Staff)
                Forms\Components\Hidden::make('user_id')
                ->default(function () {
                    return Auth::id(); // Mengambil user_id yang sedang login
                }),
                Forms\Components\TextInput::make('total')
                    ->label('Quantity')
                    ->rules(
                        'required',  // Kolom harus diisi
                    )
                    ->validationMessages([
                       'required' => 'Product stock is low.', 
                    ])
                    ->placeholder('Enter quantity value')
                    ->numeric()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $productId = $get('product_id');
                        if ($productId) {
                            $product = Product::find($productId);
                            if ($product) {
                                $finalStock = $product->final_stock;
                                $name = $product->name_product;
                
                                if ($state > $finalStock) {
                                    Notification::make()
                                        ->title('Stok Tidak Cukup')
                                        ->body("Stok {$name} saat ini hanya {$finalStock} unit.")
                                        ->danger()
                                        ->persistent()
                                        ->send();
                                    $set('total', null); // reset input jika stok tidak cukup
                                }
                            }
                        }
                    }),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code_po')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name_product')
                    ->label('Product')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('users.name')
                    ->label('Name Staff')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPreOrders::route('/'),
            // 'create' => Pages\CreatePreOrder::route('/create'),
            // 'edit' => Pages\EditPreOrder::route('/{record}/edit'),
        ];
    }    
}
