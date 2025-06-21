<?php

namespace App\Filament\Auth;

use App\Models\Team;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as AuthRegister;

class Register extends AuthRegister
{
    public function form(Form $form): Form
    {
        return $form->schema([
            $this->getNameFormComponent(),
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getPasswordConfirmationFormComponent(),

            Select::make('team_id')
                ->label('Team')
                ->options(fn() => Team::pluck('name_team', 'id')) // Mengambil data team dari tabel
                ->searchable()
                ->required(),

            TextInput::make('role_id')
                ->label('Roles')
                ->hidden()
                ->default(3)
        ])
            ->statePath('data');
    }
}
