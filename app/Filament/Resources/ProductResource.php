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
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\Pages\ViewProduct;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\ProductResource\RelationManagers\SupplierRelationManager;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Products Management'; // navigasi group
    public static function form(Form $form): Form
    {
        $isEdit = !is_null($form->getRecord());
        $isCreate = is_null($form->getRecord()); // menyembunyikan saat create
        $isView = request()->routeIs('*view');
        return $form
            // membuat code barang otomatis 
            ->schema([
                Section::make()
                    ->columns(4)
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
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(fn(string $context) => $context === 'view' ? 1 : 2), // mengatur column

                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->options(Supplier::all()->pluck('name_supplier', 'id'))
                            ->reactive()
                            ->required()
                            ->visible(!$isView)
                            ->searchable()
                            ->columnSpan(fn(string $context) => $context === 'view' ? 1 : 1), // mengatur column

                        Forms\Components\TextInput::make('stock')
                            ->label('Quantity')
                            ->required()
                            ->numeric()
                            ->live() // membuat generet otomatis ketika input quantity pada field quantity
                            ->hidden($isEdit) // disembunyikan saat edit
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                // Update final stock
                                $set('final_stock', self::countFinalStock($get));

                                // Hitung total price juga
                                $set('total_price', (float) ($get('price') ?? 0) * (float) ($get('stock') ?? 0));
                            })
                            ->columnSpan(fn(string $context) => $context === 'view' ? 1 : 2),

                        // Output final stock
                        TextInput::make('final_stock')
                            ->label('Stock')
                            ->numeric()
                            ->required()
                            ->disabled() // agar tidak bisa diisi manual
                            ->columnSpan(fn(string $context) => $context === 'view' ? 1 : 2),

                        Forms\Components\TextInput::make('price')
                            ->label('Price')
                            ->required()
                            ->numeric()
                            ->hidden($isEdit) // disembunyikan saat edit
                            ->live() // membuat generet otomatis
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $set('total_price', (float) ($get('price') ?? 0) * (float) ($get('stock') ?? 0));
                            })
                            ->columnSpan(fn(string $context) => $context === 'view' ? 1 : 2),
                        TextInput::make('total_price')
                            ->label('Total Price')
                            ->disabled() // tidak bisa diketik manual
                            ->numeric()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $price = (float) $get('price');
                                $stock = (int) $get('stock');
                                $set('total_price', $price * $stock);
                            })
                            ->columnSpan(fn(string $context) => $context === 'view' ? 1 : 2),
                    ])

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
                    ->label('Price /Item')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Price')
                    ->money('IDR')
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
                    ->color(fn(string $state): string => match (true) {
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
            ->headerActions([
                FilamentExportHeaderAction::make('Export')
                    ->modalHeading('Export')
                    ->modalDescription('Select the file format and data you want to export.')
                    ->color('gray')
                    ->size('xs')
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
            ->with('supplier'); // menambahkan data relation pada supplier
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
        ];
    }

    // Middleware untuk Hak Akses Superadmin, Admin, User
    public static function canViewAny(): bool
    {
        return Auth::user()?->hasRole(['SuperAdmin', 'Admin']);
    }
    public static function canView(Model $record): bool
    {
        return Auth::user()?->hasRole(['SuperAdmin', 'Admin']);
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->hasRole(['SuperAdmin', 'Admin']);
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()?->hasRole(['SuperAdmin', 'Admin']);
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()?->hasRole(['SuperAdmin', 'Admin']);
    }

    // Menghitung Final Stock
    protected static function countFinalStock(Get $get): int
    {
        return (int) $get('stock') + (int) $get('in_stock') - (int) $get('out_stock');
    }
}
