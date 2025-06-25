<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Team;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\TeamResource\Pages;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Organization'; // navigasi group

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name_team')
                    ->required()
                    ->maxLength(255),
                Select::make('division_id')
                    ->label('Division')
                    ->relationship('division', 'name_division') // relasi Eloquent ke model Team
                    ->searchable()
                    ->preload() // preload untuk menghindari delay
                    ->required(),
                Select::make('position_id')
                    ->label('Position')
                    ->relationship('position', 'name_position') // relasi Eloquent ke model Team
                    ->searchable()
                    ->preload() // preload untuk menghindari delay
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_team')
                    ->searchable(),
                Tables\Columns\TextColumn::make('division.name_division')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position.name_position')
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            // 'create' => Pages\CreateTeam::route('/create'),
            // 'edit' => Pages\EditTeam::route('/{record}/edit'),
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
