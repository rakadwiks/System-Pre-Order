<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Position;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\PositionResource\Pages;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Master'; // navigasi group

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name_position')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_position')
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
            'index' => Pages\ListPositions::route('/'),
            // 'create' => Pages\CreatePosition::route('/create'),
            // 'edit' => Pages\EditPosition::route('/{record}/edit'),
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
}
