<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PreOrderResource\Pages;
use App\Filament\Resources\PreOrderResource\RelationManagers;
use App\Models\PreOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PreOrderResource extends Resource
{
    protected static ?string $model = PreOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code_po')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('id_product')
                    ->label('Product')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('id_users')
                    ->label('Name Staff')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('id_supplier')
                    ->label('Supplier')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('total')
                    ->label('Total')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('description')
                    ->label('Description')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code_po')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_product')
                    ->label('Product')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_users')
                    ->label('Name Staff')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('id_supplier')
                    ->label('Supplier')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->searchable(),
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
