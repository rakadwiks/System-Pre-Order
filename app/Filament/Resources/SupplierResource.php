<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Regency;
use App\Models\Supplier;
use Filament\Forms\Form;
use App\Models\Provinces;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SupplierResource\Pages;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationGroup = 'Products Management'; // navigasi group

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name_supplier')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('Phone Number')
                    ->tel()
                    ->required()
                    ->mask('9999-9999-9999')
                    ->maxLength(15),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                Select::make('province_id')
                    ->label('Province')
                    ->required()
                    ->options(Provinces::all()->pluck('name', 'id'))
                    ->reactive()
                    ->searchable()
                    ->afterStateUpdated(fn($set) => $set('regency_id', null)),

                Select::make('regency_id')
                    ->label('Regency')
                    ->required()
                    ->options(fn(callable $get) =>
                    Regency::where('province_id', $get('province_id'))->pluck('name', 'id'))
                    ->reactive()
                    ->searchable(),
                Forms\Components\TextInput::make('country')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('postal_code')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_supplier')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                TextColumn::make('province.name')->label('Provinsi')
                    ->searchable(),
                TextColumn::make('regency.name')->label('Kabupaten')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->searchable(),
                Tables\Columns\TextColumn::make('postal_code')
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
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['province']);
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
            'index' => Pages\ListSuppliers::route('/'),
            // 'create' => Pages\CreateSupplier::route('/create'),
            // 'edit' => Pages\EditSupplier::route('/{record}/edit'),
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
