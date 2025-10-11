<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->required(),
                TextInput::make('telefone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('cpf'),
                TextInput::make('cep'),
                TextInput::make('logradouro'),
                TextInput::make('bairro'),
                TextInput::make('cidade'),
                TextInput::make('estado'),
                TextInput::make('complemento'),
                TextInput::make('numero'),
            ]);
    }
}
