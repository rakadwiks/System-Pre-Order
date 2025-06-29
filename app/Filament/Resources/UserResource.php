<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\UserResource\Pages;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Permission'; // navigasi group

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Select::make('team_id')
                    ->label('Team')
                    ->relationship('team', 'name_team') // relasi Eloquent ke model Team
                    ->searchable()
                    ->preload() // preload untuk menghindari delay
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->maxLength(255)
                    ->required(fn(string $context) => $context === 'create')
                    ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn($state) => filled($state)),
                Radio::make('role_id')
                    ->label('Roles')
                    ->options([
                        1 => 'Superadmin',
                        2 => 'Admin',
                        3 => 'User',
                    ])
                    ->inline()
                    ->inlineLabel(false)
                    ->default(3)  // Defaultnya adalah 'user' ketika registrasi
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('role.name')
                    ->badge()
                    ->colors([
                        'gray' => 'User',
                        'warning' => 'Admin',
                        'danger' => 'SuperAdmin',
                    ]),
                Tables\Columns\TextColumn::make('team.name_team') // Mengambil nama dari tabel teams
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
            'index' => Pages\ListUsers::route('/'),
        ];
    }

    // Middleware untuk Hak Akses Superadmin, Admin, User
    public static function canViewAny(): bool
    {
        return Auth::user()?->hasRole(['SuperAdmin']);
    }
    public static function canView(Model $record): bool
    {
        return Auth::user()?->hasRole(['SuperAdmin']);
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->hasRole(['SuperAdmin']);
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()?->hasRole(['SuperAdmin']);
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()?->hasRole(['superadmin']);
    }
}
