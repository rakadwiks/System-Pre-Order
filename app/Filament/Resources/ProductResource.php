<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Supplier;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\Pages\ViewProduct;
use App\Filament\Resources\ProductResource\RelationManagers\SupplierRelationManager;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function form(Form $form): Form
    {
        $isEdit = !is_null($form->getRecord());
        $isCreate = is_null($form->getRecord()); // menyembunyikan saat create
        $isView = request()->routeIs('*view');
        return $form
            // membuat code barang otomatis 
            ->schema([
                Forms\Components\TextInput::make('code_product')
                ->default(function () {
                    $randomNumber = random_int(1, 9999);
                    return 'IT-' . str_pad($randomNumber, 4, '0', STR_PAD_LEFT);
                })
                ->readOnly()
                ->disabled()
                ->dehydrated(),
                Forms\Components\TextInput::make('name_product')
                    ->required()
                    ->maxLength(255),
                Select::make('supplier_id')
                    ->label('Supplier')
                    ->options(Supplier::all()->pluck('name_supplier', 'id'))
                    ->reactive()
                    ->visible(!$isView)
                    ->searchable(),
                
                Forms\Components\TextInput::make('stock')
                    ->label('Quantity')
                    ->numeric()
                    ->required()
                    ->live()
                    ->hidden($isEdit), // disembunyikan saat edit
                Forms\Components\TextInput::make('price')
                    ->label('Price')
                    ->required()
                    ->reactive()
                    ->afterStateHydrated(function ($state, callable $set) {
                        if ($state) {
                            $formatted = number_format($state, 0, ',', '.');
                            $set('price', $formatted);
                        }
                    })
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Hapus titik sebelum disimpan ke state
                        $numeric = str_replace('.', '', $state);
                        $set('price', intval($numeric));
                    })
                    ->extraAttributes([
                        'x-data' => '{}',
                        'x-init' => 'this.addEventListener("input", function(e) {
                            let value = e.target.value.replace(/\./g, "");
                            if (!isNaN(value)) {
                                e.target.value = Number(value).toLocaleString("id-ID");
                            }
                        })',
                    ]),                        
                Forms\Components\TextInput::make('final_stock')
                        ->label('Stock')
                        ->numeric()
                        ->default(fn (Get $get) => $get('stock') + $get('in_stock') - $get('out_stock'))
                        ->readOnly()
                        ->disabled()
                        ->dehydrated(),
        ]);
    }

    public static function table(Table $table): Table
    {
        
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code_product')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_product')
                    ->searchable(),
                Tables\Columns\TextColumn::make('supplier.name_supplier')
                    ->label('Suppliers')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('in_stock')
                    ->label('IN')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('out_stock')
                    ->label('OUT')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('final_stock')
                    ->label('Stock')
                    ->numeric()
                    ->sortable()
                    ->color(fn (string $state): string => match (true) {
                        intval($state) < 0 => 'danger', // Warna merah jika nilai negatif
                        default => 'success', // Warna hijau jika nilai positif atau 0
                    }),
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
                Tables\Actions\ViewAction::make(),                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('supplier'); // 👈 Tambahkan ini
    }
    
    public static function getRelations(): array
    {
        
        return [
            SupplierRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'view' => ViewProduct::route('/{record}'), 
            // 'create' => Pages\CreateProduct::route('/create'),
            // 'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    // Middleware untuk Hak Akses Superadmin, Admin, User
    public static function canViewAny(): bool
    {
        return Auth::user()?->hasRole(['superadmin', 'admin']);
    }
    public static function canView(Model $record): bool
    {
        return Auth::user()?->hasRole(['superadmin', 'admin']);
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->hasRole(['superadmin', 'admin']);
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()?->hasRole(['superadmin', 'admin']);
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()?->hasRole(['superadmin', 'admin']);
    }
}
