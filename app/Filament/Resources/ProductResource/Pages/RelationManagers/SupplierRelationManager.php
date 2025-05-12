<?php 

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Forms;

class SupplierRelationManager extends RelationManager
{
   // Nama relasi di model Product
   protected static string $relationship = 'supplier';

   // Form untuk menampilkan data supplier yang terkait
   public function form(Forms\Form $form): Forms\Form
   {
       return $form->schema([
           // Kita hanya ingin menampilkan data, tidak perlu form input
           Forms\Components\TextInput::make('name_supplier')->label('Supplier Name')->required(),
           Forms\Components\TextInput::make('phone')->label('Supplier Contact')->required(),
           Forms\Components\TextInput::make('address')->label('Address')->required(),
           Forms\Components\TextInput::make('province.name')->label('Province')->required(),
           Forms\Components\TextInput::make('regency.name')->label('Regency')->required(),
       ]);
   }

   // Tabel untuk menampilkan daftar supplier yang terkait
   public function table(Tables\Table $table): Tables\Table
   {
       return $table->columns([
           Tables\Columns\TextColumn::make('name_supplier')->label('Supplier Name'),
           Tables\Columns\TextColumn::make('phone')->label('Supplier Contact'),
            Tables\Columns\TextColumn::make('address')->label('Address'),
            Tables\Columns\TextColumn::make('province.name')->label('Province'),
            Tables\Columns\TextColumn::make('regency.name')->label('Regency'),
       ]);
   }
}
